# Projeto integrador Reciboexpress
Projeto integrador II Eixo Computação 2026

Recibo Express
Plataforma web para emissão rápida, padronizada e digital de recibos.
Projeto desenvolvido para o Projeto Integrador em Computação II da Univesp – Universidade Virtual do Estado de São Paulo, eixo Computação, 2026.
Disciplina	PJI240 – Projeto Integrador em Computação II, Turma	A2026S2N2, Grupo	16


# Sobre o projeto
Muitos profissionais autônomos ainda emitem recibos à mão, em blocos de papel ou em modelos improvisados de editor de texto. Isso gera documentos sem padrão, difíceis de arquivar, fáceis de perder e trabalhosos de consultar depois.
O Recibo Express resolve esse problema oferecendo uma plataforma simples onde o usuário cadastra seus dados uma única vez e, a partir daí, gera recibos completos em poucos cliques — com valor por extenso, numeração automática e opção de download ou compartilhamento.

# Objetivos
Agilizar a emissão de recibos para pessoas físicas e pequenos negócios;
Padronizar as informações obrigatórias de um recibo (pagador, recebedor, valor, data, referência);
Manter um histórico organizado e pesquisável dos recibos emitidos;
Reduzir o uso de papel e facilitar o envio digital ao cliente.

# Público-alvo
Profissionais autônomos e prestadores de serviço Pessoa física que prestam serviços para pessoa física;

# Funcionalidades
 Cadastro e login de usuários
 Cadastro dos dados do emissor (nome, CPF, endereço, contato)
 Cadastro de clientes/pagadores
 Emissão de recibo com preenchimento automático do valor por extenso
 Numeração sequencial automática
 Visualização e download do recibo em PDF
 Compartilhamento do recibo (e-mail / WhatsApp)
 Histórico de recibos com busca e filtros por data, cliente e valor

 
# Tecnologias utilizadas
Frontend (Interface) - HTML, CSS e JavaScript, com Bootstrap 5.3.8 para estrutura e responsividade. O projeto também utiliza JavaScript Vanilla e jQuery para interações na interface. 
Backend & API/Acessibilidade - PHP 8.2.35, executado no servidor por meio de PHP-FPM/FastCGI. As integrações externas API´s são:ViaCEP, para consulta de CEP; Google Identity / OAuth 2.0 e OpenID Connect, para autenticação com conta Google; VLibras, para acessibilidade em Libras
Dados e Nuvem - Banco de dados: o documento declara MySQL 11.4.2.; Nuvem e Hospedagem/servidor: King Host. A aplicação e os arquivos PDF de recibos e orçamentos ficam no ambiente de hospedagem da King Host, em servidor com Red Hat Enterprise Linux 8.10
Versionamento	Git e GitHub


# Equipe – Grupo 16
Alexandre Bessa de Oliveira, RA 24202882
Carlos Alexandre dos Santos Rocha, RA 2207168
Daiany da Silva Duarte, RA 24227207
Elenir Freitas de Sousa, RA 24210548
Felipe Silva Rocha de Oliveira, RA 24217060
Gabrielly Alves de Menezes, RA 24209662
Geraldo Henrique Oliveira Soares, RA 24211113
Leandro de Oliveira, RA 25213428

Orientador(a): Leonarda Barbosa

# Licença
Projeto acadêmico desenvolvido para fins educacionais no âmbito do Projeto Integrador da Univesp. Distribuído sob a licença MIT — veja o arquivo LICENSE.
