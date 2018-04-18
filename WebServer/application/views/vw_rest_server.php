<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>REST Server Tests</title>
    <style>
    ::selection { background-color: #E13300; color: white; }
    ::-moz-selection { background-color: #E13300; color: white; }
    body {
        background-color: #FFF;
        margin: 40px;
        font: 16px/20px normal Helvetica, Arial, sans-serif;
        color: #4F5155;
        word-wrap: break-word;
    }
    a {
        color: #039;
        background-color: transparent;
        font-weight: normal;
    }
    h1 {
        color: #444;
        background-color: transparent;
        border-bottom: 1px solid #D0D0D0;
        font-size: 24px;
        font-weight: normal;
        margin: 0 0 14px 0;
        padding: 14px 15px 10px 15px;
    }
    code {
        font-family: Consolas, Monaco, Courier New, Courier, monospace;
        font-size: 16px;
        background-color: #f9f9f9;
        border: 1px solid #D0D0D0;
        color: #002166;
        display: block;
        margin: 14px 0 14px 0;
        padding: 12px 10px 12px 10px;
    }
    #body {
        margin: 0 15px 0 15px;
    }
    p.footer {
        text-align: right;
        font-size: 16px;
        border-top: 1px solid #D0D0D0;
        line-height: 32px;
        padding: 0 10px 0 10px;
        margin: 20px 0 0 0;
    }
    #container {
        margin: 10px;
        border: 1px solid #D0D0D0;
        box-shadow: 0 0 8px #D0D0D0;
    }
    </style>
</head>
<body>
<div id="container">
    <h1>REST Server Tests</h1>
    <div id="body">
        <h2>Web Service - API BRD - Test</h2>

        <p>DESENVOLVENDO UMA NOVA FERRAMENTA (somente servico da Web / API. Nenhuma interface de usuario necessaria.)</p>
		<p>Historias de Usuarios:<br></p>
		
		<p><b>(1) Como usuario convidado, quero ver os itens da minha lista de tarefas, para que eu possa ver em quais tarefas preciso trabalhar.</b>
		<br><br>
		CRITERIOS DE ACEITACAO:<br>
		1- A API deve ser desenvolvida usando conceitos e praticas REST.<br>
    	2- O usuario deve poder obter a lista de todas as tarefas existentes.<br>
    	3- O usuario deve poder obter os detalhes de uma tarefa.<br>
    	4- Caso nao haja nenhuma tarefa, a API deve retornar a seguinte mensagem: "Uau. Voce nao tem mais nada para fazer. Aproveite o resto do seu dia!".
        </p>

        <p><b>(2) Como usuario convidado, desejo adicionar um novo item ah minha lista de tarefas, para que eu possa armazenar minhas tarefas.</b>
		<br><br>
		CRITERIOS DE ACEITACAO:<br>
		1- A API deve ser desenvolvida usando conceitos e praticas REST.<br>
    	2- O sistema nao deve permitir tarefas vazias. Se isso acontecer, a API deve retornar a seguinte mensagem: "Movimentacao incorreta! Tente remover a tarefa em vez de excluir seu conteudo".<br>
    	3- O sistema deve definir a data em que o item foi criado automaticamente.<br>
    	4- O UUID deve ser gerado automaticamente e deve ser exclusivo.<br>
    	5- O tipo de tarefa deve permitir apenas "compras" ou "trabalho". Se outro tipo for passado, a API deve retornar a seguinte mensagem: "O tipo de tarefa que voce forneceu nao e suportado. Voce pode usar somente compras ou trabalho."<br>
        </p>

		<p><b>(3) Como usuario convidado, desejo excluir uma tarefa da minha lista de tarefas, para que eu possa descartar as tarefas que nao serao mais necessarias.</b>
		<br><br>
		CRITehRIOS DE ACEITAcaO:<br>
		1- A API deve ser desenvolvida usando conceitos e praticas REST.<br>
    	2- O usuario deve poder excluir uma tarefa existente.<br>
    	3- Se a tarefa nao eh mais valida, a API deve retornar a seguinte mensagem: "Boas noticias! A tarefa que voce estava tentando excluir nao existe."<br>
		</p>
		
		<p><b>(4) Como usuario convidado, quero priorizar as tarefas da minha lista de tarefas, para que eu possa organizar meu trabalho e sempre entregar as coisas mais valiosas primeiro.</b>
		<br><br>
		CRITERIOS DE ACEITACAO PARA ITEM 4 e 5:<br>
		1- A API deve ser desenvolvida usando conceitos e praticas REST.<br>
    	2- O usuario deve poder editar as informacoes de uma tarefa existente.<br>
    	3- Se a tarefa nao existir, a API deve retornar a seguinte mensagem: "Voce eh um hacker ou algo do tipo? A tarefa que voce estava tentando editar nao existe."<br>
    	4- O usuario deve poder reordenar a lista com base em seus critehrios de priorizacao.<br>
    	5- Se a tarefa compartilhar a mesma prioridade de outra tarefa existente, o sistema deve ser inteligente o suficiente para reordenar toda a lista e evitar conflitos de prioridade.<br>
		<p>
		
        <p>Clique nos links para verificar as respostas do Servidor REST.</p>
        <ol>
            <li><a href="<?php echo site_url('api/WebServiceBRD/tasks'); ?>">User Storie 1</a> - Listar tarefas</li>           
            <li><a href="<?php echo site_url('api/WebServiceBRD/add'  ); ?>">User Strore 2</a> - Adicionar tarefas</li>
            <li><a href="<?php echo site_url('api/WebServiceBRD/del'  ); ?>">User Strore 3</a> - Apagar tarefas</li>
            <li><a href="<?php echo site_url('api/WebServiceBRD/upd'  ); ?>">User Strore 4</a> - Alterar tarefas</li>
        </ol>
    </div>
    <p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  '<strong>'.CI_VERSION.'</strong>' : '' ?></p>
</div>
<script src="https://code.jquery.com/jquery-1.12.0.js"></script>
<script>
    // Create an 'App' namespace
    var App = App || {};
    // Basic rest module using an IIFE as a way of enclosing private variables
    App.rest = (function restModule(window) {
        // Fields
        var _alert = window.alert;
        var _JSON = window.JSON;

        // Cache the jQuery selector
        var _$ajax = null;

        // Cache the jQuery object
        var $ = null;

        // Methods (private)

        /**
         * Called on Ajax done
         *
         * @return {undefined}
         */
        function _ajaxDone(data) {
            // The 'data' parameter is an array of objects that can be iterated over
            _alert(_JSON.stringify(data, null, 2));
        }

        /**
         * Called on Ajax fail
         *
         * @return {undefined}
         */
        function _ajaxFail() {
            _alert('Oh no! A problem with the Ajax request!');
        }

        /**
         * On Ajax request
         *
         * @param {jQuery} $element Current element selected
         * @return {undefined}
         */
        function _ajaxEvent($element) {
            $.ajax({
                    // URL from the link that was 'clicked' on
                    url: $element.attr('href')
                })
                .done(_ajaxDone)
                .fail(_ajaxFail);
        }

        /**
         * Bind events
         *
         * @return {undefined}
         */
        function _bindEvents() {
            // Namespace the 'click' event
            _$ajax.on('click.app.rest.module', function (event) {
                event.preventDefault();

                // Pass this to the Ajax event function
                _ajaxEvent($(this));
            });
        }

        /**
         * Cache the DOM node(s)
         *
         * @return {undefined}
         */
        function _cacheDom() {
            _$ajax = $('#ajax');
        }

        // Public API
        return {
            /**
             * Initialise the following module
             *
             * @param {object} jQuery Reference to jQuery
             * @return {undefined}
             */
            init: function init(jQuery) {
                $ = jQuery;

                // Cache the DOM and bind event(s)
                _cacheDom();
                _bindEvents();
            }
        };
    }(window));

    // DOM ready event
    $(function domReady($) {
        // Initialise the App module
        App.rest.init($);
    });
</script>
</body>
</html>