import sys
import cv2
import numpy as np
import json

# Arguments
image_path = sys.argv[1]
output_path = sys.argv[2]

# Target dimensions (fixed)
TARGET_WIDTH = 2481
TARGET_HEIGHT = 3509

# Load image
img = cv2.imread(image_path)
original_height, original_width = img.shape[:2]

# Resize image to target dimensions
img = cv2.resize(img, (TARGET_WIDTH, TARGET_HEIGHT))
gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
blur = cv2.GaussianBlur(gray, (5, 5), 0)

# Detect circles
circles = cv2.HoughCircles(
    blur, cv2.HOUGH_GRADIENT, 1, 20,
    param1=50, param2=30, minRadius=10, maxRadius=30
)

def cluster_positions(positions, threshold=15):
    clusters = []
    for pos in sorted(positions):
        added = False
        for i, cluster in enumerate(clusters):
            center, members = cluster
            if abs(pos - center) < threshold:
                members.append(pos)
                new_center = sum(members) / len(members)
                clusters[i] = (new_center, members)
                added = True
                break
        if not added:
            clusters.append((pos, [pos]))
    centers = [c[0] for c in clusters]
    return centers

def process_region(circles, x_min, x_max, y_min, y_max, start_question):
    rows = {}
    row_threshold = 30
    for x, y, r in circles:
        if x_min <= x <= x_max and y_min <= y <= y_max:
            added = False
            for row_y in list(rows.keys()):
                if abs(int(y) - int(row_y)) < row_threshold:
                    rows[row_y].append((x, y, r))
                    added = True
                    break
            if not added:
                rows[y] = [(x, y, r)]

    sorted_rows = sorted(rows.items(), key=lambda item: item[0])
    question_number = start_question
    questions = []
    for _, bubbles in sorted_rows:
        sorted_bubbles = sorted(bubbles, key=lambda b: b[0])
        filled_option = None
        for idx, (x, y, r) in enumerate(sorted_bubbles):
            option = chr(65 + idx)  # A, B, C, D
            roi = gray[max(0, y - r):min(gray.shape[0], y + r),
                       max(0, x - r):min(gray.shape[1], x + r)]
            mean_intensity = np.mean(roi)
            filled = mean_intensity < 100
            color = (0, 255, 0) if filled else (0, 0, 255)
            cv2.circle(img, (x, y), r, color, 2)
            cv2.putText(img, f'Q{question_number}{option}', (x - r, y - r - 10),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.5, color, 2)
            if filled:
                filled_option = option

        if filled_option is None:
            questions.append({"id": question_number, "answer": None})
        else:
            questions.append({"id": question_number, "answer": filled_option})

        question_number += 1

    return questions

def process_id_region(circles, x_min, x_max, y_min, y_max):
    filtered = [(x, y, r) for x, y, r in circles if x_min <= x <= x_max and y_min <= y <= y_max]
    if len(filtered) == 0:
        return ""

    xs = [x for x, y, r in filtered]
    ys = [y for x, y, r in filtered]

    row_centers = cluster_positions(ys, threshold=20)
    col_centers = cluster_positions(xs, threshold=20)

    if len(row_centers) != 10 or len(col_centers) != 10:
        # ID grid incomplete
        return ""

    grid = {(r, c): None for r in range(10) for c in range(10)}

    for x, y, r in filtered:
        row = min(range(10), key=lambda i: abs(y - row_centers[i]))
        col = min(range(10), key=lambda i: abs(x - col_centers[i]))
        grid[(row, col)] = (x, y, r)

    student_id_digits = []
    for col in range(10):
        filled_digit = '?'
        for row in range(10):
            circle = grid.get((row, col))
            if circle is None:
                continue
            x, y, r = circle
            roi = gray[max(0, y - r):min(gray.shape[0], y + r),
                       max(0, x - r):min(gray.shape[1], x + r)]
            mean_intensity = np.mean(roi)
            filled = mean_intensity < 100
            color = (0, 255, 0) if filled else (0, 0, 255)
            cv2.circle(img, (x, y), r, color, 2)
            cv2.putText(img, f'ID{row}-{col}', (x - r, y - r - 10),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.5, color, 1)
            if filled:
                filled_digit = str(row)
        student_id_digits.append(filled_digit)

    return ''.join(student_id_digits)

results = {
    "answers": [],
    "student_id": ""
}

if circles is not None:
    circles = np.uint16(np.around(circles[0]))

    # Draw rectangles for visualization (optional)
    cv2.rectangle(img, (300, 300), (830, 3200), (255, 0, 0), 3)      # Region 1
    cv2.rectangle(img, (830, 300), (1400, 3200), (255, 0, 0), 3)     # Region 2
    cv2.rectangle(img, (1450, 1650), (2250, 2500), (0, 255, 255), 3) # ID region

    results["answers"].extend(process_region(circles, 300, 830, 300, 3200, 1))
    results["answers"].extend(process_region(circles, 830, 1400, 300, 3200, 36))
    results["student_id"] = process_id_region(circles, 1450, 2250, 1650, 2500)

# Save annotated image
cv2.imwrite(output_path, img)

# Output JSON to stdout
print(json.dumps(results))
