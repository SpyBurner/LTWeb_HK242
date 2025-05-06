<?php
$base_style = 'alert position-fixed bottom-0 end-0 p-3 d-none text-white rounded-3 shadow-lg';
?>

<script>
    function showAlert(message, type = "error", duration = 3000) {
        const alertBox = document.getElementById("alert-box");
        alertBox.querySelector("p").innerText = message;

        // Change color based on type
        alertBox.className = ` <?php echo $base_style ?> ${
            type === "success" ? "bg-success" :
                type === "warning" ? "bg-warning" : "bg-error"
        }`;

        alertBox.classList.remove("d-none");

        gsap.to(alertBox, { opacity: 1, scale: 1, duration: 0.5 });

        setTimeout(() => {
            gsap.to(alertBox, { opacity: 0, scale: 0.9, duration: 0.5, onComplete: () => alertBox.classList.add("d-none") });
        }, duration);
    }
</script>


<!-- Floating Alert -->
<div id="alert-box" class="d-none" style="z-index: 1050;">
    <p><i class="bi bi-exclamation-triangle-fill"></i></p>
</div>


<?php
function showAlert($message, $type = "error", $duration = 3000) {
    echo "<script>showAlert('$message', '$type', '$duration');</script>\n";
}
?>

