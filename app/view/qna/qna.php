<?php
    use model\MessageModel;
    use model\QnaEntryModel;
    use model\UserModel;

    assert(isset($qnaPage));
    assert(isset($qna));
    assert(isset($faq));
    assert(isset($maxPage));
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
            let target = null;
            if (window.location.hash === '#qna')
                target = document.getElementById('qna');
            else if (window.location.hash === '#add_question')
                target = document.getElementById('add_question');
            if (target === null)
                return;
            target.scrollIntoView({behavior: 'smooth'});
        }

        function redirectToQnaDetail(qnaid){
            // Update the URL with the new qnaid
            var newUrl = BASE_URL + '/qna?qnaid=' + qnaid + '#qna';

            // Check if the current URL is the same as the new URL
            if (window.location.href !== newUrl) {
                window.location.href = newUrl;  // Navigate to the new URL
            } else {
                // Force reload if the URL is the same
                window.location.reload();
            }
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
<!--                            <br>-->
<!--                            or use the search bar below.-->
                        </p>
<!--                        <div class="join">-->
<!--                            <label class="input flex items-center join-item">-->
<!--                                <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">-->
<!--                                    <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2.5" fill="none" stroke="currentColor">-->
<!--                                        <circle cx="11" cy="11" r="8"></circle>-->
<!--                                        <path d="m21 21-4.3-4.3"></path>-->
<!--                                    </g>-->
<!--                                </svg>-->
<!--                                <input type="search" class="grow bg-transparent outline-none px-2" placeholder="Search" />-->
<!--                            </label>-->
<!--                            <button class="btn join-item btn-primary">Search</button>-->
<!--                        </div>-->
                    </div>
                </div>
            </div>
        </section>

        <div class="divider"></div>

        <section class="max-w-5xl mx-auto p-6 ">
            <h2 class="text-3xl font-bold text-center mb-6">Frequently Asked Questions</h2>

            <div class="space-y-4 max-h-96 overflow-y-auto">
                <?php
                    foreach ($faq as $entry){
                ?>
                    <div class="card bg-base-100 shadow-md">
                        <div class="collapse collapse-arrow">
                            <input type="checkbox" />
                            <div class="collapse-title text-lg font-medium">
                                <?= $entry->getQuestion();?>
                            </div>
                            <div class="collapse-content">
                                <p><?= $entry->getAnswer(); ?></p>
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </section>

        <div class="divider"></div>

        <section id="qna" class="max-w-5xl mx-auto p-6">
            <h2 class="text-3xl font-bold text-center mb-6">Questions And Answers</h2>
            <div class="space-y-4">
                <?php
                    foreach ($qna as $entry){
                        if (!($entry instanceof QnaEntryModel)) {
                            throw new TypeError("Expected QnaEntryModel");
                        }
                ?>
                <!-- QNA Item 1 -->
                <div class="cursor-pointer card bg-base-100 shadow-md">
                    <div class="collapse-title text-lg font-medium" onclick="redirectToQnaDetail(<?= $entry->getQnaid(); ?>)">
                        <?= $entry->getMessage()->getContent(); ?>
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
            <div class="join flex justify-center mt-6">
                <button class="join-item btn" onclick="setQnaPage(1)">«</button>
                <button class="join-item btn" onclick="setQnaPage(<?= ($qnaPage > 0)? $qnaPage - 1 : $qnaPage?>)">‹</button>

                <button class="join-item btn btn-disabled <?php echo ($qnaPage <= PAGINATION_NUMBER)? 'hidden' : '' ?>" onclick="setQnaPage()">...</button>

                <?php
                    $pageMin = max(1, $qnaPage - PAGINATION_NUMBER);
                    $pageMax = min($maxPage, $qnaPage + PAGINATION_NUMBER);
                    for ($index = $pageMin; $index <= $pageMax; $index++) {
                ?>
                    <button class="join-item btn <?php echo ($qnaPage == $index )? 'btn-primary' : '' ?>"
                            onclick="setQnaPage(<?= $index ?>)"><?=$index?></button>
                <?php
                    }
                ?>

                <button class="join-item btn btn-disabled <?php echo ($qnaPage >= $maxPage - PAGINATION_NUMBER)? 'hidden' : '' ?>" onclick="setQnaPage()">...</button>

                <button class="join-item btn" onclick="setQnaPage(<?= ($qnaPage < $maxPage)? $qnaPage + 1 : $qnaPage?>)">›</button>
                <button class="join-item btn" onclick="setQnaPage(<?= $maxPage?>)">»</button>
            </div>
        </section>

        <!-- Thread Modal -->
        <dialog id="thread" class="modal max-h-screen">
            <?php
                if (isset($msgs_data)){
            ?>
            <div class="modal-box max-w-5xl">
                <h3 id="thread_question" class="text-lg font-bold"><?=$msgs_data[0]['msg']->getContent()?></h3>
                <div class="border-t my-4"></div>

                <div class="overflow-y-scroll max-h-96">
                    <?php
                        foreach ($msgs_data as $item){
                            $msg = $item['msg'];
                            $user = $item['user'];
                            $avatar = $item['avatar'];

                            if (!($msg instanceof MessageModel)) {
                                throw new TypeError("Expected MessageModel");
                            }
                            if (!($user instanceof UserModel)) {
                                throw new TypeError("Expected UserModel");
                            }
                    ?>
                            <!-- Threaded Messages -->
                            <div class="space-y-4 max-h-60 overflow-y-auto">
                                <!-- Message 1 -->
                                <div class="card bg-base-100 shadow-md p-4">
                                    <div class="flex items-start gap-4">
                                        <!-- Profile Picture -->
                                        <div class="avatar">
                                            <div class="w-12 rounded-full">
                                                <img src="<?=$avatar?>" alt="User Avatar">
                                            </div>
                                        </div>
                                        <!-- Message Content -->
                                        <div>
                                            <div class="font-bold">
                                                <?= $user->getUsername() ?>
                                                <span class="text-sm text-gray-500">• <?= $msg->getSenddate() ?></span>
                                            </div>
                                            <p class="text-gray-700"><?= $msg->getContent() ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    ?>

                </div>
                <div class="border-t my-4"></div>

                <!-- Form to add a new message -->
                <form method="post" action="/qna/add_message" class="flex flex-col gap-4">
                    <label class="form-control w-full">
<!--                        <span class="label-text">Your reply</span>-->
                        <textarea id="thread-textarea" name="message" placeholder="Type your reply..." class="textarea rounded-none textarea-bordered w-full resize-none" rows="3"></textarea>
                    </label>

                    <input type="number" name="qnaid" value="<?= $msgs_data[0]['msg']->getQnaid();?>" hidden="hidden">

                    <!-- Action Buttons -->
                    <div class="modal-action">
                        <button class="btn btn-primary" type="submit">Send</button>
                        <button class="btn" type="button" onclick="document.getElementById('thread').close()">Close</button>
                    </div>
                </form>
            </div>

            <?php
                }
            ?>
        </dialog>

        <div class="divider"></div>

        <section id="add_question" class="max-w-5xl mx-auto p-6 items-center">
            <h2 class="text-3xl font-bold text-center mb-6">Not what you are looking for? Leave us a question!</h2>
            <!-- Floating Chat Button -->
            <div class="flex justify-center">
                <button class="btn btn-primary" type="button"
                        onclick="document.getElementById('chat-modal').showModal(); window.location.hash = 'add_question';">
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
                    <form method="post" action="/qna/add_question">
                        <label class="form-control w-full">
                            <span class="label-text">Your Question</span>
                            <textarea name="question" class="textarea textarea-bordered w-full" placeholder="Type your question here..."></textarea>
                        </label>

                        <!-- Buttons -->
                        <div class="modal-action">
                            <button class="btn btn-primary" type="submit" onclick="window.location.hash = 'add_question';">Submit</button>
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

    if (isset($msgs_data)){
        echo '<script>';
        echo 'openModal("thread");';
        echo '</script>';
    }
?>
</body>

</html>