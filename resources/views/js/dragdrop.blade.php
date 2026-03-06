<script>
const overlay = document.getElementById("dropOverlay");

document.addEventListener("dragover", e=>{
    e.preventDefault();
    overlay.style.display="flex";
});

document.addEventListener("dragleave", e=>{
    if(e.clientX === 0 && e.clientY === 0){
        overlay.style.display="none";
    }
});

document.addEventListener("drop", e=>{
    e.preventDefault();
    overlay.style.display="none";

    const files = e.dataTransfer.files;

    if(files.length){
        uploadFiles(files);
    }
});

function uploadFiles(files){

    const formData = new FormData();

    formData.append("path","{{ $path }}");

    for(let file of files){
        formData.append("files[]",file);
    }

    const xhr = new XMLHttpRequest();

    const progress = document.getElementById("uploadProgress");
    const bar = document.getElementById("progressBar");

    progress.style.display="block";

    xhr.upload.addEventListener("progress", e=>{

        if(e.lengthComputable){

            const percent = (e.loaded/e.total)*100;
            bar.style.width = percent+"%";

        }

    });

    xhr.onload = ()=>{

        if(xhr.status===200){

            showToast("Upload concluído");

            setTimeout(()=>location.reload(),500);

        }else{

            showToast("Erro no upload");

        }

    };

    xhr.open("POST","{{ route('files.upload') }}");

    xhr.setRequestHeader("X-CSRF-TOKEN","{{ csrf_token() }}");

    xhr.send(formData);
}
</script>