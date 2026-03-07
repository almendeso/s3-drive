<script>
function showToast(message, timeout = 2500){

    const toast = document.getElementById("toast");

    if(!toast) return;

    toast.innerText = message;
    toast.style.display = "block";
    toast.style.opacity = "1";

    if(timeout > 0){
        setTimeout(()=>{
            toast.style.opacity = "0";
            setTimeout(()=>toast.style.display="none",300);
        },timeout);
    }
}
</script>

