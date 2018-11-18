<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
        <main>
          <!-- Flash Message -->
    <?php if ($this->session->flashdata('message')): ?>
          <div class="alert alert-primary" role="alert">
            <div class="alert-message">
              <?php echo $this->session->flashdata('message')['message']; ?>
              <?php $this->session->set_flashdata('message'); ?>
            </div>
          </div>
    <?php endif; ?>

          <div class="clearfix">
            <form class="form-inline float-right mt--1 d-none d-md-flex">
              <button class="btn btn-primary"><i class="align-middle" data-feather="plus"></i> New project</button>
            </form>
            <h1 class="h3 mb-3">Dashboard</h1>
          </div>
          <a href="/index.php/auth/logout"><button id="logout" class="ui button">Logout</button></a>



            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Bootstrap Markdown</h5>
                    <h6 class="card-subtitle text-muted">Simple Markdown editing tools that works.</h6>
                  </div>
                  <div class="card-body">
                    <form>
                      <textarea name="content" data-provide="markdown" rows="14"></textarea>
                    </form>
                  </div>
                </div>
              </div>




              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h5 class="card-title">Quill</h5>
                    <h6 class="card-subtitle text-muted">Modern WYSIWYG editor built for compatibility and extensibility.</h6>
                  </div>
                  <div class="card-body">
                    <div class="clearfix">
                      <div id="quill-toolbar">
                        <span class="ql-formats">
              <select class="ql-font"></select>
              <select class="ql-size"></select>
            </span>
                        <span class="ql-formats">
              <button class="ql-bold"></button>
              <button class="ql-italic"></button>
              <button class="ql-underline"></button>
              <button class="ql-strike"></button>
            </span>
                        <span class="ql-formats">
              <select class="ql-color"></select>
              <select class="ql-background"></select>
            </span>
                        <span class="ql-formats">
              <button class="ql-script" value="sub"></button>
              <button class="ql-script" value="super"></button>
            </span>
                        <span class="ql-formats">
              <button class="ql-header" value="1"></button>
              <button class="ql-header" value="2"></button>
              <button class="ql-blockquote"></button>
              <button class="ql-code-block"></button>
            </span>
                        <span class="ql-formats">
              <button class="ql-list" value="ordered"></button>
              <button class="ql-list" value="bullet"></button>
              <button class="ql-indent" value="-1"></button>
              <button class="ql-indent" value="+1"></button>
            </span>
                        <span class="ql-formats">
              <button class="ql-direction" value="rtl"></button>
              <select class="ql-align"></select>
            </span>
                        <span class="ql-formats">
              <button class="ql-link"></button>
              <button class="ql-image"></button>
              <button class="ql-video"></button>
            </span>
                        <span class="ql-formats">
              <button class="ql-clean"></button>
            </span>
                      </div>
                      <div id="quill-editor"></div>
                    </div>
                  </div>
                </div>
              </div>


        </main>

            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ"
             crossorigin="anonymous">

            <script>
              document.addEventListener("DOMContentLoaded", function(event) {
                if (!window.Quill) {
                  return $('#quill-editor,#quill-toolbar,#quill-bubble-editor,#quill-bubble-toolbar').remove();
                }
                var editor = new Quill('#quill-editor', {
                  modules: {
                    toolbar: '#quill-toolbar'
                  },
                  placeholder: 'Type something',
                  theme: 'snow'
                });
                var bubbleEditor = new Quill('#quill-bubble-editor', {
                  placeholder: 'Compose an epic...',
                  modules: {
                    toolbar: '#quill-bubble-toolbar'
                  },
                  theme: 'bubble'
                });
              });
            </script>
