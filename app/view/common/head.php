<!--PLACE THIS IN <head> TAG-->
<?php
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>💖Cakezone💖</title>

<!-- icons -->
<script src="https://kit.fontawesome.com/d2a9bab36d.js" crossorigin="anonymous"></script>
<!-- daisyui -->
<link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
<link href="https://cdn.jsdelivr.net/npm/daisyui@5.0.0/themes.css" rel="stylesheet" type="text/css" />
<!-- tailwindcss -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<style type="text/tailwindcss">
    .brand-name {
        @apply text-gray-500;
    }

    .product-name {
        @apply text-lg font-semibold text-black;
    }

    .price {
        @apply text-xl text-red-700;
    }

    .sold-amt {
        @apply text-sm text-gray-500;
    }

    @media (max-width: 768px) {
        #search-nav, #login-register {
            display: none;
        }

        #header-content {
            @apply gap-2 w-11/12;
        }
    }

</style>

<?php
?>