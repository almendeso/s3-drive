<style>
body {
    font-family: system-ui;
    background:#f8f9fa;
    margin:0;
    padding:30px;
}
h2 { margin-bottom:20px; font-weight:500; }

.toolbar {
    display:flex;
    gap:12px;
    flex-wrap:wrap;
    margin-bottom:20px;
}
input, button {
    padding:7px 12px;
    border-radius:8px;
    border:1px solid #dadce0;
    font-size:14px;
}
button {
    background:#1a73e8;
    color:white;
    border:none;
    cursor:pointer;
}
button:hover { background:#1558c0; }

table {
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 1px 3px rgba(0,0,0,0.08);
    table-layout:fixed;
}

thead {
    background:#f1f3f4;
    color:#5f6368;
    font-size:13px;
    text-transform:uppercase;
}

th, td {
    padding:14px 16px;
    border-bottom:1px solid #eee;
    vertical-align:middle;
    white-space:nowrap;
    overflow:hidden;
    text-overflow:ellipsis;
    text-align:left; /* 🔑 tudo alinhado à esquerda */
}

.col-size   { width:130px; }
.col-date   { width:190px; }
.col-actions{ width:120px; }

.name { display:flex; gap:10px; align-items:center; }
.folder { color:#1a73e8; text-decoration:none; font-weight:500; }

.actions {
    display:flex;
    gap:14px;
    justify-content:flex-start;
}

.icon-btn {
    cursor:pointer;
    opacity:.6;
}
.icon-btn:hover { opacity:1; }

tr:hover { background:#f8f9fa; }
</style>
