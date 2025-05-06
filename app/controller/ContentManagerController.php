<?php
namespace controller;


use core\Controller;
use core\SessionHelper;
use http\Header;
use service\ProductsService;
use service\CategoryService;
use service\OrderService;
use const config\ADMIN_CONFIG_URL;
use const config\STATIC_IMAGE_URL;

class ContentManagerController extends BaseController {
    public function index() {
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $carousel1 = $config['carousel1'] ?? '';
        $carousel2 = $config['carousel2'] ?? '';
        $carousel3 = $config['carousel3'] ?? '';
        $contactNumber = $config['contactNumber'] ?? '';
        $address = $config['address'] ?? '';
        $maxDisplayedProduct = $config['maxDisplayedProductsForHomePage'] ?? '';
        $logo = $config['logo'] ?? '';
        $slogan = $config['slogan'] ?? '';
        $banner = $config['banner'] ?? '';
        $aboutUs = $config['aboutUs'] ?? '';

        $partners = $config['partners'] ?? [];

        $this->render('/admin/content-manager', [
            'title' => 'Content Manager',
            'carousel1' => $carousel1,
            'carousel2' => $carousel2,
            'carousel3' => $carousel3,
            'contactNumber' => $contactNumber,
            'address' => $address,
            'maxDisplayedProduct' => $maxDisplayedProduct,
            'logo' => $logo,
            'slogan' => $slogan,
            'banner' => $banner,
            'aboutUs' => $aboutUs,
            'partners' => $partners,
        ]);
    }

    public function edit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section = $_POST['section'] ?? '';
            if ($section === 'carousel') {
                $this->editCarousel();
            } elseif ($section === 'contact') {
                $this->editContactInfo();
            } elseif ($section === 'display') {
                $this->editDisplayConfig();
            } elseif ($section === 'introduction') {
                $this->editIntroduction();
            } elseif ($section === 'partner') {
                $this->editPartner();
            } else {
                $this->redirectWithMessage('/admin/content-manager', [
                    'error' => 'Invalid section'
                ]);
            }
        } else {
            $this->redirectWithMessage('/admin/content-manager', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    private function editCarousel() { // upload files
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $config = $this->saveStaticImage('carousel1', $config);
        $config = $this->saveStaticImage('carousel2', $config);
        $config = $this->saveStaticImage('carousel3', $config);
        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'Carousel images updated successfully'
        ]);
    }

    private function saveStaticImage($configKey, $config) {
        $savePath = __DIR__ . '/../../public/assets/img/';
        if (isset($_FILES[$configKey]) && $_FILES[$configKey]['error'] == UPLOAD_ERR_OK) {
            $file = $_FILES[$configKey];
//            $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
            if ($config[$configKey] !== '') {
                // delete the old file
                $oldFile = $savePath . $config[$configKey];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
            $fileName = basename($file['name']);
            $newFile = $savePath . "{$configKey}_{$fileName}";
            move_uploaded_file($file['tmp_name'], $newFile);
            $config[$configKey] =  "{$configKey}_{$fileName}";
//            file_put_contents(ADMIN_CONFIG_URL, json_encode($config));
        }
        return $config;
    }

    private function editContactInfo() {
        $contactNumber = $_POST['phone'];
        $address = $_POST['address'];

        // save to JSON file
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $config['contactNumber'] = $contactNumber;
        $config['address'] = $address;
        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'Contact information updated successfully'
        ]);
    }

    private function editDisplayConfig() {
        $maxProducts = $_POST['max-display'];

        // save to JSON file
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $config['maxDisplayedProductsForHomePage'] = $maxProducts;
        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'Display configuration updated successfully'
        ]);
    }

    private function editIntroduction() {
        $slogan = $_POST['slogan'];
        $aboutUs = $_POST['aboutUs'];

        // save to JSON file
        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $config['slogan'] = $slogan;
        $config['aboutUs'] = $aboutUs;

        $config = $this->saveStaticImage('logo', $config);
        $config = $this->saveStaticImage('banner', $config);
        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'About Us updated successfully'
        ]);
    }

    private function editPartner() {
        $name = $_POST['name'];
        $id = $_POST['id'];

        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        $partner = $config['partners'][$id] ?? null;
        if ($partner) {
            $partner['name'] = $name;
            $partner = $this->saveStaticImage('logo', $partner);
            $config['partners'][$id] = $partner;
        }
        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'Partners updated successfully'
        ]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section = $_POST['section'] ?? '';
            if ($section === 'partner') {
                $this->addPartner();
            } else {
                $this->redirectWithMessage('/admin/content-manager', [
                    'error' => 'Invalid section'
                ]);
            }
        } else {
            $this->redirectWithMessage('/admin/content-manager', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    private function addPartner() {
        $name = $_POST['name'];

        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        // create a new partner
        $partner = [
            'name' => $name,
            'logo' => '',
            'url' => '',
        ];

        $partner = $this->saveStaticImage('logo', $partner);
        $config['partners'][] = $partner;

        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'Partners added successfully'
        ]);
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $section = $_POST['section'] ?? '';
            if ($section === 'partner') {
                $this->deletePartner();
            } else {
                $this->redirectWithMessage('/admin/content-manager', [
                    'error' => 'Invalid section'
                ]);
            }
        } else {
            $this->redirectWithMessage('/admin/content-manager', [
                'error' => 'Invalid request method'
            ]);
        }
    }

    private function deletePartner() {
        $id = $_POST['id'];

        $config = json_decode(file_get_contents(ADMIN_CONFIG_URL), true);
        // delete the partner
        // temporary not delete logo since they are saved as same prefix
//        if (isset($config['partners'][$id]['logo'])) {
//            $oldFile = "{STATIC_IMAGE_URL}/partner_{$config['partners'][$id]['logo']}";
//            if (file_exists($oldFile)) {
//                unlink($oldFile);
//            }
//        }
        unset($config['partners'][$id]);
        file_put_contents(ADMIN_CONFIG_URL, json_encode($config));

        $this->redirectWithMessage('/admin/content-manager', [
            'success' => 'Partners deleted successfully'
        ]);
    }
}