<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function ensure_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

function save_jpeg($img, string $path, int $quality = 85): void
{
    imagejpeg($img, $path, $quality);
}

function resize_to_width($srcImg, int $srcW, int $srcH, int $targetW)
{
    if ($srcW <= $targetW) {
        $targetW = $srcW;
    }
    $ratio = $targetW / $srcW;
    $targetH = (int) round($srcH * $ratio);

    $dst = imagecreatetruecolor($targetW, $targetH);
    imagecopyresampled($dst, $srcImg, 0, 0, 0, 0, $targetW, $targetH, $srcW, $srcH);
    return $dst;
}

function process_upload_image(array $file, string $publicImagesDir): ?string
{
    if (empty($file) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $tmp = $file['tmp_name'];
    $info = getimagesize($tmp);

    if ($info === false) {
        return null;
    }

    $mime = $info['mime'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($mime, $allowed, true)) {
        return null;
    }

    $createFn = match ($mime) {
        'image/jpeg' => 'imagecreatefromjpeg',
        'image/png' => 'imagecreatefrompng',
        'image/webp' => 'imagecreatefromwebp',
        default => null
    };

    if ($createFn === null)
        return null;

    $srcImg = $createFn($tmp);
    if (!$srcImg)
        return null;

    $srcW = $info[0];
    $srcH = $info[1];

    $fullDir = rtrim($publicImagesDir, '/') . '/fullsize';
    $thumbDir = rtrim($publicImagesDir, '/') . '/thumbs';

    ensure_dir($fullDir);
    ensure_dir($thumbDir);

    $filename = bin2hex(random_bytes(8)) . '_' . time() . '.jpg';

    $fullImg = resize_to_width($srcImg, $srcW, $srcH, 720);
    save_jpeg($fullImg, $fullDir . '/' . $filename, 85);
    imagedestroy($fullImg);

    $thumbImg = resize_to_width($srcImg, $srcW, $srcH, 320);
    save_jpeg($thumbImg, $thumbDir . '/' . $filename, 85);
    imagedestroy($thumbImg);

    imagedestroy($srcImg);

    return $filename;
}
function searchCatalogue(PDO $pdo, string $keyword = '', string $country = '', string $foodType = '', string $spiceLevel = '', string $priceRange = '', int $limit = 9, int $offset = 0): array
{
    $keyword = "%" . $keyword . "%";

    $sql = "SELECT * FROM catalogue_items
            WHERE (title LIKE ? OR description LIKE ? OR country LIKE ? OR mainIngredients LIKE ? OR cookingMethod LIKE ?)
              AND (country = ? OR ? = '')
              AND (foodType = ? OR ? = '')
              AND (spiceLevel = ? OR ? = '')
              AND (priceRange = ? OR ? = '')
            ORDER BY id DESC
            LIMIT ? OFFSET ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $keyword, $keyword, $keyword, $keyword, $keyword,
        $country, $country,
        $foodType, $foodType,
        $spiceLevel, $spiceLevel,
        $priceRange, $priceRange,
        $limit, $offset
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
