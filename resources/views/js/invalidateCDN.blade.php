<script>

function invalidateCDN(path,file,btn){

    if(!confirm("Invalidar cache CDN deste arquivo?"))
        return;

    btn.innerHTML="⏳";

    fetch("{{ route('files.invalidate') }}",{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({
            path:path,
            file:file
        })
    })
    .then(r=>{
        if(!r.ok) throw new Error("HTTP error");
        return r.json();
    })
    .then(data=>{

        btn.innerHTML="✅";

        showToast("CDN invalidado: " + data.file);

        setTimeout(()=>{
            btn.innerHTML="♻️";
        },2000);

    })
    .catch(err=>{
        console.error(err);
        btn.innerHTML="⚠️";
        showToast("Erro ao invalidar CDN");
    });

}

function invalidateFolder(path,btn){

    if(!confirm("Invalidar cache CDN desta pasta?"))
        return;

    btn.innerHTML="⏳";

    fetch("{{ route('files.invalidateFolder') }}",{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body:JSON.stringify({
            path:path
        })
    })
    .then(r=>r.json())
    .then(data=>{

        btn.innerHTML="✅";

        showToast("CDN invalidado: " + data.folder);

        setTimeout(()=>{
            btn.innerHTML="🔄";
        },2000);

    })
    .catch(err=>{
        console.error(err);
        btn.innerHTML="⚠️";
        showToast("Erro ao invalidar pasta");
    });

}

</script>