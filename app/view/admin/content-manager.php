<?php
assert(isset($title));
assert(isset($contactNumber));
assert(isset($address));
assert(isset($maxDisplayedProduct));
assert(isset($slogan));
assert(isset($aboutUs));
assert(isset($partners));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <?php require_once __DIR__ . "/../common/admin-link.php"; ?>
</head>

<body>
    <div id="app">
        <?php require_once __DIR__ . "/../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once __DIR__ . "/../common/admin-header.php"; ?>

            <!-- put the main content here -->
            <div class="page-heading">
                <h3>Content Manager</h3>
            </div>

            <section id="basic-horizontal-layouts">
                <div class="row">
                    <!-- Carousel -->
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Carousel</h4>
                                <small>Images should have a ratio of 5:2</small>
                            </div>
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" enctype="multipart/form-data" action="/admin/content-manager/edit">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="slide-1">Slide 1</label>
                                            </div>

                                            <input type="hidden" name="section" value="carousel" />

                                            <div class="col-md-8 form-group">
                                                <input type="file" id="slide-1" class="form-control" name="carousel1" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="slide-2">Slide 2</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" id="slide-2" class="form-control" name="carousel2" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="slide-3">Slide 3</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" id="slide-3" class="form-control" name="carousel3" />
                                            </div>

                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Contact information -->
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Contact Information</h4>
                            </div>
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" action="/admin/content-manager/edit">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Contact Number</label>
                                            </div>

                                            <input type="hidden" name="section" value="contact" />

                                            <div class="col-md-8 form-group">
                                                <input type="number" class="form-control" name="phone" value="<?= htmlspecialchars($contactNumber) ?>" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Address</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" class="form-control" name="address" value="<?= htmlspecialchars($address) ?>" />
                                            </div>

                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Display config -->
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Display Configuration</h4>
                            </div>
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" action="/admin/content-manager/edit">
                                    <div class="form-body">
                                        <div class="row">
                                            <input type="hidden" name="section" value="display" />

                                            <div class="col-md-4">
                                                <label for="">Max displayed products for Home page</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="number" class="form-control" name="max-display" required min="2" max="20" value="<?= htmlspecialchars($maxDisplayedProduct) ?>" />
                                                <small class="d-block text-end">Integer between 2 to 20</small>
                                            </div>

                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Introduction -->
                    <div class="col-md-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Introduction</h4>
                            </div>
                            <div class="card-body">
                                <form class="form form-horizontal" method="POST" enctype="multipart/form-data" action="/admin/content-manager/edit">
                                    <div class="form-body">
                                        <div class="row">
                                            <input type="hidden" name="section" value="introduction" />
                                            <div class="col-md-4">
                                                <label for="">Logo</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" class="form-control" name="logo" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Slogan</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" class="form-control" name="slogan" value="<?= htmlspecialchars($slogan) ?>" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Banner</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" class="form-control" name="banner" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">About us</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <textarea class="form-control" name="aboutUs" rows="4"><?= htmlspecialchars($aboutUs) ?></textarea>
                                            </div>

                                            <div class="col-sm-12 d-flex justify-content-end">
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Partners -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Partners</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <?php foreach ($partners as $index => $partner) : ?>
                                    <div class="col-6 col-md-4">
                                        <div class="p-3 bg-white border rounded text-center shadow-sm team-member">
                                            <form method="POST" enctype="multipart/form-data" action="/admin/content-manager/edit">
                                                <input type="hidden" name="section" value="partner" />
                                                <input type="hidden" name="id" value="<?= $index ?>" />

                                                <div class="mb-3">
                                                    <label class="form-label">Logo</label>
                                                    <input type="file" class="form-control form-control-sm" name="logo" />
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($partner['name']) ?>" />
                                                </div>

                                                <div class="d-flex justify-content-center gap-2">
                                                    <button onclick="deleteMember(this)" class="btn btn-danger btn-sm">Delete</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                        <?php endforeach; ?>
                                </div>

                                <!-- Add New Team Member Button -->
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                                        Add Team Member
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for Adding Team Member -->
                        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addMemberModalLabel">Partner Information</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="/admin/content-manager/add" enctype="multipart/form-data">
                                            <input type="hidden" name="section" value="partner" />

                                            <div class="mb-3">
                                                <label class="form-label">Logo</label>
                                                <input type="file" class="form-control form-control-sm" name="logo"/>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" name="name"/>
                                            </div>

                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-success px-4">Save</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        // Delete Team Member
        function deleteMember(button) {
            // let teamCard = button.closest(".team-member");
            // teamCard.remove();
            // send the current form to delete api
            let form = button.closest("form");
            form.action = "/admin/content-manager/delete";
            form.method = "POST";
            form.submit();
        }
    </script>

    <?php require_once __DIR__ . "/../common/admin-script.php"; ?>
</body>

</html>