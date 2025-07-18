<div id="messagePopup" class="popupOuter hidden bg-gray-700/75 fixed h-[100vh] w-full flex justify-center items-center z-[9999]">
    <div class="popup p-8 rounded-lg max-w-1/2 bg-white border-gray-400 border-2 flex flex-col items-center justify-center shadow-lg">
        <div id="popupmessage" class="message text-xl pb-4 text-center">Hello There...</div>
        <button class="messagebtn rounded-lg font-bold text-[green] text-4xl" onclick="closePopup()"><i class="fa-solid fa-circle-check"></i></button>
    </div>
</div>
<script>
    const messagePopup = document.getElementById('messagePopup');
    const popupmessage = document.getElementById('popupmessage');
    function showPopup(msg, reload=false){
        popupmessage.textContent = msg;
        messagePopup.classList.remove('hidden');
        if(reload){
            messagePopup.classList.add('reload');
        }
    }
    function closePopup(){
        popupmessage.textContent = 'Hello There...';
        messagePopup.classList.add('hidden');
        if(messagePopup.classList.contains('reload')){
            window.location.reload();
        }
    }
</script>