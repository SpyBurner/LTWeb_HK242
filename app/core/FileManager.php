<?php
namespace core;
use const config\IMAGE_UPLOAD_URL;
use const config\IMAGE_UPLOAD_URL_PUBLIC;
use const config\NO_IMAGE_URL_PUBLIC;

class FileCategory
{
    const AVATAR = 'avatar';
    const PRODUCT = 'product';
}

/**
 * FileManager class for handling file operations.
 * Images are stored in public/assets/.repo directory, in subdirectories of /<FileCategory>/<FileId>.
 * Use product/user id as the file id.
 * Only 1 file is allowed per id.
 */
class FileManager
{
    private static $instance = null;

    private function __construct()
    {
        // Private constructor to prevent instantiation
    }

    public static function getInstance(): ?FileManager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Find the first file in the directory for the given id and category.
     *
     * @param string $id The ID of the file.
     * @param FileCategory $category The category of the file.
     * @return array An array containing 'success' and 'path' keys.
     */
    private function Find($id, $category)
    {
        $dirPath = IMAGE_UPLOAD_URL . '/' . $category . '/' . $id;

        if (is_dir($dirPath)) {
            // Scan the directory (excluding "." and "..")
            $files = array_diff(scandir($dirPath), array('.', '..'));


            // If there is at least one file, return its path
            if (count($files) > 0) {
                $result = IMAGE_UPLOAD_URL_PUBLIC . '/' . $category . '/' . $id . '/' . reset($files);
                return ['success' => true, 'path' => $result];
            }
        }

        return ['success' => false, 'message' => 'File not found'];
    }

    private function FindPath($path)
    {
        $dirPath = IMAGE_UPLOAD_URL . '/' . $path;

        if (is_dir($dirPath)) {
            // Scan the directory (excluding "." and "..")
            $files = array_diff(scandir($dirPath), array('.', '..'));

            // If there is at least one file, return its path
            if (count($files) > 0) {
                $result = IMAGE_UPLOAD_URL_PUBLIC . '/' . $path . '/' . reset($files);
                return ['success' => true, 'path' => $result];
            }
        }

        return ['success' => false, 'message' => 'File not found'];
    }

    private function FindWithFallback($id, $category)
    {
        $filePath = $this->Find($id, $category);
        if ($filePath['success']) {
            return ['success' => true, 'path' => $filePath['path']];
        }

        // Fallback to default avatar URL
        return ['success' => false, 'path' => NO_IMAGE_URL_PUBLIC, 'message' => 'No image found, using default image'];
    }

    /**
     * Save the uploaded file to the specified directory.
     *
     * @param array $file The uploaded file information from $_FILES.
     * @param string $id The ID of the file.
     * @param string $category The category of the file. Acquired from FileCategory class.
     * @param bool $overwrite Whether to overwrite the existing file.
     * @return array An array containing 'success' and 'path' keys.
     */
    public function Save($file, $id, $category, $overwrite = false)
    {
        $dirPath = IMAGE_UPLOAD_URL . '/' . $category . '/' . $id;
        Logger::log("Saving file to: $dirPath");
        // Make sure the directory exists
        if (is_dir($dirPath)) {
            if ($overwrite)
                $this->Delete($id, $category);
            else return ['success' => false, 'message' => 'File already exists and overwrite is not used'];
        }
        mkdir($dirPath, 0755, true);

        $path = $dirPath . '/' . $file['name'];
        $ret_path = IMAGE_UPLOAD_URL_PUBLIC . '/' . $category . '/' . $id . '/' . $file['name'];

        if (move_uploaded_file($file['tmp_name'], $path)) {
            return ['success' => true, 'data' => $ret_path];
        }
        return ['success' => false, 'message' => 'Failed to move uploaded file'];
    }

    /**
     * Delete the file for the given id and category.
     *
     * @param string $id The ID of the file.
     * @param string $category The category of the file.
     * @return array An array containing 'success' and 'message' keys.
     */
    public function Delete($id, $category)
    {
        $dirPath = IMAGE_UPLOAD_URL . '/' . $category . '/' . $id;

        if (is_dir($dirPath)) {
            // Scan the directory (excluding "." and "..")
            $files = array_diff(scandir($dirPath), array('.', '..'));

            // Delete each file
            foreach ($files as $file) {
                unlink($dirPath . '/' . $file);
            }

            // Remove the directory itself
            rmdir($dirPath);
            return ['success' => true, 'message' => 'File deleted successfully'];
        }
        return ['success' => false, 'message' => 'Directory not found'];
    }
}

