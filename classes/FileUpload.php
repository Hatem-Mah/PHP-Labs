<?php

class FileUpload
{
    private string $uploadDir;
    private array $allowedExtensions;
    private int $maxSizeBytes;
    private string $error = '';

    public function __construct(
        string $uploadDir = 'uploads/',
        array $allowedExtensions = ['jpg', 'jpeg', 'png'],
        int $maxSizeMB = 2
    ) {
        $this->uploadDir = rtrim($uploadDir, '/') . '/';
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSizeBytes = $maxSizeMB * 1024 * 1024;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function handle(array $fileInput): ?string
    {
        if (!isset($fileInput['error']) || $fileInput['error'] !== UPLOAD_ERR_OK) {
            $this->error = 'Profile image is required.';
            return null;
        }

        $extension = strtolower(pathinfo($fileInput['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->allowedExtensions, true)) {
            $this->error = 'Profile image must be JPG or PNG.';
            return null;
        }

        if ((int)$fileInput['size'] > $this->maxSizeBytes) {
            $this->error = 'Profile image size must be 2MB or less.';
            return null;
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $newName = uniqid('img_', true) . '.' . $extension;
        $destination = $this->uploadDir . $newName;

        if (!move_uploaded_file($fileInput['tmp_name'], $destination)) {
            $this->error = 'Failed to save upload. Check folder permissions.';
            return null;
        }

        return $destination;
    }
}
