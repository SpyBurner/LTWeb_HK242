<?php
    $qnaPage = 1;
    if (isset($_GET['qnaPage'])) $qnaPage = $_GET['qnaPage'];

?>

<!DOCTYPE html>
<html lang="en" data-theme="valentine">

<head>
    <title>Cakezone QnA</title>
<?php
    require_once(__DIR__.'/../common/head.php');
?>

</head>

<body>
<?php
    require_once(__DIR__ . '/../common/header.php');
?>

    <script>
        function openModal(id) {
            document.getElementById(id).showModal();
        }

        function setQnaPage(page){
            window.location.hash = "qna";

            const urlParams = new URLSearchParams(window.location.search);
            if (page) urlParams.set("qnaPage", page);
            window.location.search = urlParams.toString();
        }

        function scrollToHash(){
            if (window.location.hash === '#qna')
                document.getElementById('qna').scrollIntoView({behavior: 'smooth'});
        }

        window.addEventListener('load', scrollToHash);
    </script>

    <div id="customer-mode" >
        <section class="max-w-7xl mx-auto p-6">
            <div class="hero">
                <div class="hero-content text-center">
                    <div class="max-w-md">
                        <h1 class="text-5xl font-bold">How can we help?</h1>
                        <p class="py-6">
                            Got a question? Check out our FAQ section
                            <br>
                            or type it in the search bar below.
                        </p>
                        <div class="join">
                            <label class="input flex items-center join-item">
                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <path d="m21 21-4.3-4.3"></path>
                                    </g>
                                </svg>
                                <input type="search" class="grow bg-transparent outline-none px-2" placeholder="Search" />
                            </label>
                            <button class="btn join-item btn-primary">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <section class="max-w-5xl mx-auto p-6 ">
            <h2 class="text-3xl font-bold text-center mb-6">Frequently Asked Questions</h2>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="card bg-base-100 shadow-md">
                    <div class="collapse collapse-arrow">
                        <input type="checkbox" />
                        <div class="collapse-title text-lg font-medium">
                            What is DaisyUI?
                        </div>
                        <div class="collapse-content">
                            <p>DaisyUI is a Tailwind CSS component library that provides pre-styled UI components.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="card bg-base-100 shadow-md">
                    <div class="collapse collapse-arrow">
                        <input type="checkbox" />
                        <div class="collapse-title text-lg font-medium">
                            How do I install DaisyUI?
                        </div>
                        <div class="collapse-content">
                            <p>You can install it via npm: <code>npm install daisyui</code>, then add it to your Tailwind config.</p>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="card bg-base-100 shadow-md">
                    <div class="collapse collapse-arrow">
                        <input type="checkbox" />
                        <div class="collapse-title text-lg font-medium">
                            Is DaisyUI free to use?
                        </div>
                        <div class="collapse-content">
                            <p>Yes! DaisyUI is completely free and open-source.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="divider"></div>


        <section id="qna" class="max-w-5xl mx-auto p-6">
            <h2 class="text-3xl font-bold text-center mb-6">Questions And Answers</h2>
            <div class="space-y-4">
                <!-- QNA Item 1 -->
                <div class="cursor-pointer card bg-base-100 shadow-md">
                    <div class="collapse-title text-lg font-medium" onclick="openModal('thread')">
                        What is DaisyUI?
                    </div>
                </div>

                <!-- QNA Item 2 -->
                <div class="cursor-pointer card bg-base-100 shadow-md">
                    <div class="collapse-title text-lg font-medium" onclick="openModal('thread')">
                        How do I install DaisyUI?
                    </div>
                </div>

                <!-- QNA Item 3 -->
                <div class="cursor-pointer card bg-base-100 shadow-md">
                    <div class="collapse-title text-lg font-medium" onclick="openModal('thread')">
                        Is DaisyUI free to use?
                    </div>
                </div>
            </div>
            <div class="join flex justify-center mt-6">
                <button class="join-item btn">«</button>
                <button class="join-item btn">‹</button>
                <button class="join-item btn btn-disabled <?php echo ($qnaPage <= 5)? 'hidden' : '' ?>" onclick="setQnaPage()">...</button>
                <button class="join-item btn <?php echo ($qnaPage == 1)? 'btn-primary' : '' ?>" onclick="setQnaPage(1)">1</button>
                <button class="join-item btn <?php echo ($qnaPage == 2)? 'btn-primary' : '' ?>" onclick="setQnaPage(2)">2</button>
                <button class="join-item btn <?php echo ($qnaPage == 3)? 'btn-primary' : '' ?>" onclick="setQnaPage(3)">3</button>
                <button class="join-item btn <?php echo ($qnaPage == 4)? 'btn-primary' : '' ?>" onclick="setQnaPage(4)">4</button>
                <button class="join-item btn <?php echo ($qnaPage == 5)? 'btn-primary' : '' ?>" onclick="setQnaPage(5)">5</button>
                <button class="join-item btn btn-disabled <?php echo ($qnaPage >= 25)? 'hidden' : '' ?>" onclick="setQnaPage()">...</button>
                <button class="join-item btn">›</button>
                <button class="join-item btn">»</button>
            </div>
        </section>

        <!-- Thread Modal -->
        <dialog id="thread" class="modal">
            <div class="modal-box max-w-5xl">
                <h3 class="text-lg font-bold">What is DaisyUI?</h3>
                <div class="border-t my-4"></div>

                <!-- Threaded Messages -->
                <div class="space-y-4 max-h-60 overflow-y-auto">
                    <!-- Message 1 -->
                    <div class="card bg-base-100 shadow-md p-4 flex gap-4 items-start">
                        <!-- Profile Picture -->
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="https://i.pravatar.cc/100?img=1" alt="User Avatar">
                            </div>
                        </div>
                        <!-- Message Content -->
                        <div>
                            <div class="font-bold">John Doe <span class="text-sm text-gray-500">• 12/03/2025</span></div>
                            <p class="text-gray-700">DaisyUI is a Tailwind CSS component library that provides pre-styled UI components.</p>
                        </div>
                    </div>

                    <!-- Message 2 -->
                    <div class="card bg-base-100 shadow-md p-4 flex gap-4 items-start">
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="https://i.pravatar.cc/100?img=2" alt="User Avatar">
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">Jane Smith <span class="text-sm text-gray-500">• 30/2/2033</span></div>
                            <p class="text-gray-700">Oh nice! Does it work with Tailwind 3?</p>
                        </div>
                    </div>

                    <!-- Message 3 -->
                    <div class="card bg-base-100 shadow-md p-4 flex gap-4 items-start">
                        <div class="avatar">
                            <div class="w-12 rounded-full">
                                <img src="https://i.pravatar.cc/100?img=3" alt="User Avatar">
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">Alice Johnson <span class="text-sm text-gray-500">• 32/9/2077</span></div>
                            <p class="text-gray-700">Yes! It is fully compatible with Tailwind 3.</p>
                        </div>
                    </div>
                </div>

                <div class="border-t my-4"></div>

                <!-- Form to add a new message -->
                <form method="dialog" class="flex flex-col gap-2">
                    <textarea id="thread-textarea" placeholder="Type your reply..." class="textarea rounded-none textarea-bordered w-full resize-none" rows="3" required></textarea>

                </form>


                <!-- Close Button -->
                <div class="modal-action">
                    <form method="dialog">
                        <button class="btn btn-primary self-start">Send</button>
                        <button class="btn">Close</button>
                    </form>
                </div>
            </div>
        </dialog>

        <div class="divider"></div>

        <section class="max-w-5xl mx-auto p-6 items-center">
            <h2 class="text-3xl font-bold text-center mb-6">Not what you are looking for? Leave us a question!</h2>
            <!-- Floating Chat Button -->
            <div class="flex justify-center">
                <button class="btn btn-primary shadow-lg" onclick="document.getElementById('chat-modal').showModal()">
                    <i class="fa-solid fa-question"></i>
                    Ask a Question
                </button>
            </div>

            <!-- Modal -->
            <dialog id="chat-modal" class="modal">
                <div class="modal-box">
                    <h3 class="text-lg font-bold">Ask a Question</h3>
                    <p class="text-sm text-gray-500 mb-4">Fill out the form below, and we'll get back to you!</p>

                    <!-- Form -->
                    <form method="dialog">
                        <label class="form-control w-full">
                            <span class="label-text">Your Question</span>
                            <textarea class="textarea textarea-bordered w-full" placeholder="Type your question here..." required></textarea>
                        </label>

                        <!-- Buttons -->
                        <div class="modal-action">
                            <button class="btn btn-error">Cancel</button>
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>

                <!-- Close Modal (Esc key works automatically) -->
                <form method="dialog" class="modal-backdrop">
                    <button>Close</button>
                </form>
            </dialog>

        </section>
    </div>
<?php
    require_once(__DIR__.'/../common/footer.php');
?>
</body>

</html>