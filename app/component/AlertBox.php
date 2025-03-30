<script>
    function showAlert(message, type = "error", duration = 3000) {
        const alertBox = document.getElementById("alert-box");
        alertBox.querySelector("p").innerText = message;

        // Change color based on type
        alertBox.className = `fixed bottom-10 right-10 text-white px-6 py-3 rounded-lg shadow-lg opacity-0 scale-90 hidden ${
            type === "success" ? "bg-green-500" :
                type === "warning" ? "bg-yellow-500" : "bg-red-500"
        }`;

        alertBox.classList.remove("hidden");

        gsap.to(alertBox, { opacity: 1, scale: 1, duration: 0.5 });

        setTimeout(() => {
            gsap.to(alertBox, { opacity: 0, scale: 0.9, duration: 0.5, onComplete: () => alertBox.classList.add("hidden") });
        }, duration);
    }

    // Usage:
    // showAlert("Login successful!", "success");
    // showAlert("Password is too short!", "warning");
    // showAlert("Incorrect password!", "error");


</script>

<!-- Floating Alert -->
<div id="alert-box" class="fixed bottom-10 right-10 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg opacity-0 scale-90 hidden">
    <p><i class="fa-solid fa-triangle-exclamation"></i> Username or password is wrong</p>
</div>

<?php
    function showAlert($message, $type = "error", $duration = 3000) {
        echo "<script>showAlert('$message', '$type', '$duration');</script>\n";
    }
?>

