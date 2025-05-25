<div id="errorPopupContainer" class="translate-x-[150%] fixed top-10 right-5 shadow-lg z-50 transition-all ease-in-out duration-150">
    @if ($errors->any() || !empty($successMessage))
        <div id="errorPopup" class="text-white relative">
            @if ($errors->any())
                <div class="bg-red-500 px-4 py-2 rounded">
                    @foreach ($errors->all() as $message)
                        <span class="text-white">{{ $message }}</span><br>
                    @endforeach
                </div>
            @endif

            @if (!empty($successMessage))
                <div class="bg-green-500 px-4 py-2 rounded">
                    <span class="text-white">{{ $successMessage }}</span>
                </div>
            @endif
        </div>
    @endif
</div>
<script>
    // Error Pop Up function
    const errorPopup = document.getElementById('errorPopupContainer');

    if (errorPopup && errorPopup.innerHTML.trim().length > 0) {
        errorPopup.classList.remove('translate-x-[150%]');
        errorPopup.classList.add('translate-x-0');
        runTimer();
    }

    function runTimer() {
        setTimeout(() => {
            errorPopup.classList.remove('translate-x-0');
            errorPopup.classList.add('translate-x-[150%]');
            setTimeout(() => {
                errorPopup.innerHTML = ''; // Removes the content inside the popup
            }, 500);
        }, 3000);
    }
</script>