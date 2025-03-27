<!DOCTYPE html>
<html lang="en" data-theme="garden">

<head>
    <?php require_once "../common/head.php"; ?>
    <title>Admin | Home</title>
</head>

<body>
    <?php require_once "../common/header-admin.php"; ?>

    <div id="body" class="m-6 md:w-3/4 md:mx-auto flex flex-col gap-8">
        <h2 class="text-3xl font-bold text-center">Content Manager</h2>
        <h3 class="text-xl font-semibold text-center text-secondary">HOME</h3>

        <form action="" method="POST">
            <fieldset class="fieldset w-full bg-base-200 border border-base-300 p-4 rounded-box">
                <legend class="fieldset-legend text-xl">Carousel</legend>
                <p class="text-center">Images size should have ratio of 5:2</p>
                
                <label class="fieldset-label">Slide 1</label>
                <input type="file" class="file-input file-input-md w-full" />

                <label class="fieldset-label">Slide 2</label>
                <input type="file" class="file-input file-input-md w-full" />

                <label class="fieldset-label">Slide 3</label>
                <input type="file" class="file-input file-input-md w-full" />

                <button type="submit" class="btn btn-success mt-4 w-1/4 mx-auto">Save</button>
            </fieldset>
        </form>

        <!-- Contact info -->
        <form action="" method="POST">
            <fieldset class="fieldset w-full bg-base-200 border border-base-300 p-4 rounded-box">
                <legend class="fieldset-legend text-xl">Contact information</legend>

                <label class="fieldset-label">Contact number</label>
                <input type="text" class="input w-full" value="0916 737 162" />

                <label class="fieldset-label">Address</label>
                <input type="text" class="input w-full" value="268 Ly Thuong Kiet, P.14, Q.10, TP.HCM" />

                <label class="fieldset-label">Google Map</label>
                <input type="text" class="input w-full" value="https://maps.app.goo.gl/C7H61tNykr9gDn1U9" />

                <label class="fieldset-label">Facebook</label>
                <input type="text" class="input w-full" value="https://www.facebook.com/khoi.0802" />

                <button type="submit" class="btn btn-success mt-4 w-1/4 mx-auto">Save</button>
            </fieldset>
        </form>


        <form action="" method="POST">
            <fieldset class="fieldset w-full bg-base-200 border border-base-300 p-4 rounded-box">
                <legend class="fieldset-legend text-xl">Display configuration</legend>

                <div class="flex items-center gap-4">
                    <label class="fieldset-label">Max products to display</label>
                    <input type="number" class="input validator rounded-lg w-1/6" required min="4" max="20" value="8"/>
                    <p class="validator-hint">Integer between be 4 to 20</p>
                </div>

                <button type="submit" class="btn btn-success mt-4 w-1/4 mx-auto">Save</button>
            </fieldset>
        </form>
    </div>

    <script>
        // toggle sidebar
        const sidebar = document.getElementById("sidebar");
        const overlay = document.getElementById("overlay");
        const toggleBtn = document.getElementById("toggleSidebar");
        const closeBtn = document.getElementById("closeSidebar");
    
        function openSidebar() {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
        }
    
        function closeSidebar() {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        }
    
        toggleBtn.addEventListener("click", openSidebar);
        closeBtn.addEventListener("click", closeSidebar);
        overlay.addEventListener("click", closeSidebar);
    
        // Close sidebar on Escape key press
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") closeSidebar();
        });
    </script>

</body>

</html>