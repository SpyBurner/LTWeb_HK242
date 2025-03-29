<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission (save data, upload files, etc.)

    // Redirect to prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Content Manager</title>

    <?php require_once "../common/admin-link.php"; ?>
</head>

<body>
    <div id="app">
        <?php require_once "../common/admin-sidebar.php"; ?>

        <div id="main">
            <?php require_once "../common/admin-header.php"; ?>

            <!-- put main content here -->
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
                                <form class="form form-horizontal">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="slide-1">Slide 1</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" id="slide-1" class="form-control" name="slide1-img" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="slide-2">Slide 2</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" id="slide-2" class="form-control" name="slide2-img" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="slide-3">Slide 3</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" id="slide-3" class="form-control" name="slide3-img" />
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
                                <form class="form form-horizontal">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Contact Number</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" class="form-control" name="phone" value="0916 737 162" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Address</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" class="form-control" name="address" value="268 Ly Thuong Kiet, P.14, Q.10, TP.HCM" />
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
                                <form class="form form-horizontal">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Max products</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="number" class="form-control" name="max-display" required min="4" max="20" value="8" />
                                                <small class="d-block text-end">Integer between 4 to 20</small>
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
                                <form class="form form-horizontal">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="">Logo</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" class="form-control" name="logo-img" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Slogan</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="text" class="form-control" name="slogan" value="Treats & Sweets In A Nutshell" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">Banner</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <input type="file" class="form-control" name="banner-img" />
                                            </div>

                                            <div class="col-md-4">
                                                <label for="">About us</label>
                                            </div>
                                            <div class="col-md-8 form-group">
                                                <textarea class="form-control" name="about-us" rows="4">Welcome to **Sweet Delights**, where we bring your sweetest dreams to life...</textarea>
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
                                    <div class="col-6 col-md-4">
                                        <div class="p-3 bg-white border rounded text-center shadow-sm team-member">
                                            <form action="" method="POST">
                                                <div class="mb-3">
                                                    <label class="form-label">Logo</label>
                                                    <input type="file" class="form-control form-control-sm" />
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" class="form-control" value="Microsoft" />
                                                </div>

                                                <div class="d-flex justify-content-center gap-2">
                                                    <button onclick="deleteMember(this)" class="btn btn-danger btn-sm">Delete</button>
                                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
                                        <form action="" method="POST">
                                            <div class="mb-3">
                                                <label class="form-label">Logo</label>
                                                <input type="file" class="form-control form-control-sm" />
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Name</label>
                                                <input type="text" class="form-control" />
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
            let teamCard = button.closest(".team-member");
            teamCard.remove();
        }
    </script>

    <?php require_once "../common/admin-script.php"; ?>
</body>

</html>