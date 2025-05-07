<!DOCTYPE html>
<html lang="en" data-theme="valentine">
<head>
    <?php require_once __DIR__ . "/../common/head.php"; 
    use const config\DEFAULT_PRODUCT_IMAGE_URL;?>
    <title>CakeZone | Checkout</title>

    <style type="text/tailwindcss">
        .brand-name {
            @apply text-gray-500;
        }
        .product-name {
            @apply text-lg font-semibold text-black;
        }
        .price {
            @apply text-xl;
        }
        .sold-amt {
            @apply text-sm text-gray-500;
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../common/header.php"; ?>

    <div id="body" class="mx-6 md:w-3/4 md:mx-auto my-10">
        <?php if (!empty($messages['success'])): ?>
            <div class="alert alert-success mb-4">
                <?= htmlspecialchars($messages['success']) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($messages['error'])): ?>
            <div class="alert alert-error mb-4">
                <?= htmlspecialchars($messages['error']) ?>
            </div>
        <?php endif; ?>

        <?php
        // Fetch customer contact list
        $contactResult = \service\ContactService::findAllByCustomerId($userId);
        if (!$contactResult['success'] || empty($contactResult['data'])): ?>
            <div class="alert alert-warning mb-4">
                You have no contact information. Please <a href="/profile" class="link link-primary">add contact</a> before checking out.
            </div>
        <?php endif; ?>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Column - Payment Form -->
            <div class="w-full md:w-2/3">
                <h2 class="text-2xl font-bold mb-6">Checkout Information</h2>

                <!-- Customer Information -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Customer Information</h3>
                        <div class="space-y-4">
                            <!-- Dropdown for selecting contact -->
                            <div>
                                <label class="label">
                                    <span class="label-text">Select Contact Information</span>
                                </label>
                                <select id="contactId" class="select select-bordered w-full" onchange="loadContactInfo(this)" required>
                                    <option value="">Select a contact</option>
                                    <?php
                                    if ($contactResult['success'] && !empty($contactResult['data'])) {
                                        foreach ($contactResult['data'] as $contact) {
                                            echo '<option value="' . htmlspecialchars($contact->getContactId()) . '" 
                                                        data-name="' . htmlspecialchars($contact->getName()) . '" 
                                                        data-phone="' . htmlspecialchars($contact->getPhone()) . '" 
                                                        data-address="' . htmlspecialchars($contact->getAddress()) . '">
                                                        ' . htmlspecialchars($contact->getName()) . ' - ' . htmlspecialchars($contact->getPhone()) . '
                                                  </option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Display selected contact information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Full Name</span>
                                    </label>
                                    <input type="text" id="name" class="input input-bordered w-full" value="" readonly>
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Phone Number</span>
                                    </label>
                                    <input type="tel" id="phone" class="input input-bordered w-full" value="" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Email</span>
                                </label>
                                <input type="email" id="email" class="input input-bordered w-full" value="" required>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Delivery Address</span>
                                </label>
                                <textarea id="address" class="textarea textarea-bordered w-full" rows="2" readonly></textarea>
                            </div>
                            <div>
                                <label class="label">
                                    <span class="label-text">Order Notes</span>
                                </label>
                                <textarea id="note" class="textarea textarea-bordered w-full" rows="2" placeholder="Notes for the seller..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="card bg-base-100 shadow-sm mb-6">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Payment Methods</h3>
                        <div class="space-y-3">
                            <div class="payment-method active" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment_method" value="COD" class="radio radio-primary" checked>
                                <div>
                                    <h4 class="font-medium">Cash on Delivery (COD)</h4>
                                    <p class="text-sm text-gray-500">Pay with cash upon delivery</p>
                                </div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment_method" value="BankTransfer" class="radio radio-primary">
                                <div>
                                    <h4 class="font-medium">Bank Transfer</h4>
                                    <p class="text-sm text-gray-500">Transfer via bank account</p>
                                </div>
                            </div>
                            <div class="payment-method" onclick="selectPaymentMethod(this)">
                                <input type="radio" name="payment_method" value="Momo" class="radio radio-primary">
                                <div>
                                    <h4 class="font-medium">Momo E-Wallet</h4>
                                    <p class="text-sm text-gray-500">Pay via Momo application</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Review -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="text-lg font-semibold mb-4">Order Review</h3>
                        <div class="space-y-4">
                            <?php foreach ($cart->products as $product): ?>
                                <div class="flex items-center gap-4">
                                    <img src="/<?= htmlspecialchars($product['avatarurl'] == '' ? DEFAULT_PRODUCT_IMAGE_URL : $product['avatarurl']) ?>"
                                         alt="<?= htmlspecialchars($product['name']) ?>" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium"><?= htmlspecialchars($product['name']) ?></h4>
                                        <p class="text-gray-500"><?= number_format($product['price'], 0, ',', '.') ?> VND × <?= $product['amount'] ?></p>
                                    </div>
                                    <p class="font-semibold"><?= number_format($product['price'] * $product['amount'], 0, ',', '.') ?> VND</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="w-full md:w-1/3">
                <div class="card bg-base-100 shadow-sm p-6 sticky top-6">
                    <h2 class="text-2xl font-bold mb-6">Order Summary</h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold"><?= number_format($cart->getTotalcost(), 0, ',', '.') ?> VND</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping Fee:</span>
                            <span class="font-semibold">30,000 VND</span>
                        </div>
                        <div class="flex justify-between border-t pt-4">
                            <span class="text-gray-600">Total:</span>
                            <span class="text-xl font-bold text-red-700"><?= number_format($cart->getTotalcost() + 30000, 0, ',', '.') ?> VND</span>
                        </div>
                    </div>

                    <div class="form-control mb-6">
                        <label class="label cursor-pointer justify-start gap-3">
                            <input type="checkbox" id="terms" class="checkbox checkbox-primary" checked>
                            <span class="label-text">I agree with the <a href="#" class="link link-primary">terms and conditions</a></span>
                        </label>
                    </div>

                    <button class="btn btn-primary w-full" onclick="placeOrder(<?= $userId ?>)">
                        <i class="fas fa-shopping-bag"></i>
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . "/../common/footer.php"; ?>

    <script>
        function selectPaymentMethod(element) {
            document.querySelectorAll('.payment-method').forEach(method => {
                method.classList.remove('active');
                method.querySelector('input[type="radio"]').checked = false;
            });
            
            element.classList.add('active');
            element.querySelector('input[type="radio"]').checked = true;
        }

        function loadContactInfo(select) {
            const selectedOption = select.options[select.selectedIndex];
            document.getElementById('name').value = selectedOption.getAttribute('data-name') || '';
            document.getElementById('phone').value = selectedOption.getAttribute('data-phone') || '';
            document.getElementById('address').value = selectedOption.getAttribute('data-address') || '';
        }

        function placeOrder(userId) {
            const contactId = document.getElementById('contactId').value;
            const email = document.getElementById('email').value;
            const note = document.getElementById('note').value;
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            const termsAgreed = document.getElementById('terms').checked;

            if (!contactId || !email || !paymentMethod || !termsAgreed) {
                alert('Please select contact information, enter email, select payment method, and agree to the terms and conditions!');
                return;
            }

            const formData = new FormData();
            formData.append('userId', userId);
            formData.append('contactId', contactId);
            formData.append('email', email);
            formData.append('note', note);
            formData.append('payment_method', paymentMethod);

            fetch('/checkout/paymentValid', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `/checkout/confirmation/${data.orderId}`;
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while placing the order');
            });
        }
    </script>
</body>
</html>