
<style>
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f4f6f9;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.login-container{
    width:100%;
    max-width:420px;
    background:white;
    padding:40px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

.login-title{
    text-align:center;
    margin-bottom:30px;
    font-size:22px;
    font-weight:bold;
}

.input-group{
    margin-bottom:18px;
}

.input-group label{
    display:block;
    font-size:14px;
    margin-bottom:6px;
}

.input-group input{
    width:100%;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    font-size:14px;
}

.input-group input:focus{
    outline:none;
    border-color:#3b82f6;
}

.login-btn{
    width:100%;
    padding:12px;
    background:#3b82f6;
    color:white;
    border:none;
    border-radius:6px;
    font-size:15px;
    cursor:pointer;
}

.login-btn:hover{
    background:#2563eb;
}

.error{
    background:#ffe5e5;
    color:#b00020;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    font-size:14px;
}

.footer{
    margin-top:20px;
    text-align:center;
    font-size:12px;
    color:#666;
}

/* mobile */

@media (max-width:500px){

.login-container{
    margin:20px;
    padding:30px;
}

}

</style>
