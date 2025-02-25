<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;
use Core\Database\Database;

class AccountantAvatar
{
    /** @var array<string, mixed> $image */
    private array $image;

    /** @param array<string, mixed> $validations */
    public function __construct(
        private Model $model,
        private array $validations = ['extensions' => ['jpg', 'jpeg', 'png'], 'max_size' => 1 * 1024 * 1024]
    ) {
    }

    public function path(): string
    {
        if ($this->model->avatar_name) {
            $hash = md5_file($this->getAbsoluteSavedFilePath());
            return $this->baseDir() . $this->model->avatar_name . '?' . $hash;
        }
        return "/assets/images/defaults/avatar.png";
    }

    public function delete(): bool
    {
        if ($this->model->avatar_name) {
            $pdo = Database::getDatabaseConn();
            $pdo->beginTransaction();

            try {
                $this->removeOldImage();
                $this->model->update(['avatar_name' => null]);
                $pdo->commit();
            } catch (\Exception $e) {
                $pdo->rollBack();
                return false & throw $e;
            }
        }
        return true;
    }

    /**
     * @param array<string, mixed> $image
     */
    public function update(array $image): bool
    {
        $this->image = $image;

        if (!$this->isValidImage()) {
            return false;
        }

        if ($this->updateFile()) {
            $this->model->update([
                'avatar_name' => $this->getFileName(),
            ]);
            return true;
        }

        return false;
    }

    public function updateFile(): bool
    {
        if (empty($this->getTmpFilePath())) {
            return false;
        }

        $this->removeOldImage();

        $resp = move_uploaded_file(
            $this->getTmpFilePath(),
            $this->getAbsoluteDestinationPath()
        );

        if (!$resp) {
            $error = error_get_last();
            throw new \RuntimeException(
                'Failed to move uploaded file: ' . ($error['message'] ?? 'Unknown error')
            );
        }

        return true;
    }



    private function getTmpFilePath(): string
    {
        return $this->image['tmp_name'];
    }

    private function removeOldImage(): void
    {
        if ($this->model->avatar_name) {
            $path = Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->avatar_name);
            unlink($this->getAbsoluteSavedFilePath());
        }
    }

    private function getFileName(): string
    {
        $file_name_splitted = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);
        return 'avatar.' . $file_extension;
    }

    private function getAbsoluteDestinationPath(): string
    {
        return $this->storeDir() . $this->getFileName();
    }

    private function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    private function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());

        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

    public function getAbsoluteSavedFilePath(): string
    {
        return Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->avatar_name);
    }

    private function isValidImage(): bool
    {
        if (isset($this->validations['extensions'])) {
            $this->validateImageExtension();
        }

        if (isset($this->validations['max_size'])) {
            $this->validateImageSize();
        }

        return empty($this->model->errors('avatar'));
    }

    public function validateImageExtension(): void
    {
        $file_name_splitted = explode('.', $this->image['name']);
        $file_extension = end($file_name_splitted);

        if (!in_array($file_extension, $this->validations['extensions'])) {
            $this->model->addError('avatar', 'Extensão de arquivo inválida');
        }
    }

    public function validateImageSize(): void
    {
        if ($this->image['size'] > $this->validations['max_size']) {
            $this->model->addError('avatar', 'Tamanho de arquivo inválido');
        }
    }
}
