<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;

class CampaignImage
{
    /** @var array<string, mixed> $image */
    private array $image = [];

    public function __construct(
        private Model $model
    ) {
    }

    public function path(): string
    {
        if ($this->model->image_url) {
            return $this->baseDir() . $this->model->image_url;
        }
        return "/assets/images/defaults/campaign-placeholder.png";
    }

    /**
     * @param array<string, mixed> $image
     */
    public function update(array $image): void
    {
        $this->image = $image;

        if (!empty($this->getTmpFilePath())) {
            $this->removeOldImage();
            $fileName = $this->getFileName();
            $this->model->update(['image_url' => $fileName]);
            move_uploaded_file($this->getTmpFilePath(), $this->getAbsoluteFilePath($fileName));
        }
    }

    public function destroy(): void
    {
        $this->removeOldImage();

        $dir = (string) Constants::rootPath()->join('public' . $this->baseDir());
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    private function getTmpFilePath(): string
    {
        return $this->image['tmp_name'] ?? '';
    }

    private function removeOldImage(): void
    {
        if ($this->model->image_url) {
            $path = (string) Constants::rootPath()
                ->join('public' . $this->baseDir())
                ->join($this->model->image_url);

            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    private function getFileName(): string
    {
        $parts = explode('.', $this->image['name']);
        $extension = end($parts);
        return 'image_' . uniqid() . '.' . $extension;
    }

    private function getAbsoluteFilePath(string $fileName): string
    {
        return $this->storeDir() . $fileName;
    }

    private function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    private function storeDir(): string
    {
        $path = (string) Constants::rootPath()->join('public' . $this->baseDir());

        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }
}