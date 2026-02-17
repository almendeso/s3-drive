
i# 📁 Laravel File Manager (S3 / CDN Ready)

Gerenciador de arquivos simples, seguro e elegante desenvolvido em **Laravel 9**, inspirado em um file manager em PHP puro, com suporte a **upload**, **criação de pastas**, **exclusão**, **busca**, **navegação por diretórios** e **links via CDN (CloudFront / S3)**.

Ideal para painéis administrativos, visualizadores de arquivos e ferramentas internas.

---

## 🚀 Funcionalidades

- 📂 Navegação por pastas
- 📄 Listagem de arquivos
- 🔍 Busca por nome
- ⬆ Upload múltiplo de arquivos
- 📁 Criação de novas pastas
- 🗑️ Exclusão de arquivos
- 🌐 Geração de link público via CDN
- 📋 Copiar link para a área de transferência
- 🔒 Proteção contra *path traversal*
- ⛔ Bloqueio de extensões perigosas
- 🎨 Interface limpa (HTML + CSS puro)
- ✅ Compatível com Laravel 9 / PHP 8.1+

---

## 🧱 Estrutura do Projeto

app/
└── Http/
└── Controllers/
└── FileManagerController.php

config/
└── files.php

resources/
└── views/
└── files/
└── index.blade.php

routes/
└── web.php

storage/
└── app/
└── uploads/


---

## ⚙️ Requisitos

- PHP >= 8.1
- Laravel 9.x
- Extensões PHP:
  - fileinfo
  - mbstring
  - openssl

---

📄 Licença

Este projeto está licenciado sob a licença MIT.

👨‍💻 Autor

Desenvolvido por Alvaro Mendes
Laravel • PHP • Backend

