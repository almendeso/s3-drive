<script>
function copyLink(link) {
    navigator.clipboard.writeText(link)
        .then(() => alert('Link copiado:\n'+link));
}
</script>