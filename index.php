
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>Парсер Gift Sbp</title>

    <!-- Bootstrap core CSS -->
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
        <style>
        .spinner {
          display: inline-block;
          opacity: 0;
          width: 0;

          -webkit-transition: opacity 0.25s, width 0.25s;
          -moz-transition: opacity 0.25s, width 0.25s;
          -o-transition: opacity 0.25s, width 0.25s;
          transition: opacity 0.25s, width 0.25s;
        }

        .has-spinner.active-spinner {
          cursor:progress;
        }

        .has-spinner.active-spinner .spinner {
          opacity: 1;
          width: auto; /* This doesn't work, just fix for unkown width elements */
        }

        .has-spinner.btn-mini.active-spinner .spinner {
            width: 10px;
        }

        .has-spinner.btn-small.active-spinner .spinner {
            width: 13px;
        }

        .has-spinner.btn.active-spinner .spinner {
            width: 16px;
        }

        .has-spinner.btn-large.active-spinner .spinner {
            width: 19px;
        }
        </style>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Парсер продукции</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/public_html/parser/">Парсер</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Nav header</li>
                <li><a href="#">Separated link</a></li>
                <li><a href="#">One more separated link</a></li>
              </ul>
            </li>
          </ul>
          <form class="navbar-form navbar-right">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h2>Парсер Gifts Spb</h2>
        <p><a id="parse-btn" data-loading-text='<span class="spinner"><i class="glyphicon glyphicon-spin glyphicon-refresh"></i></span> Обработка' class="btn btn-primary btn-lg has-spinner" href="/public_html/parser?action=parse">
             Сканировать каталог &raquo;</a>
            <span id='parse-result'></span>
        </p>
      </div>
    </div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
          <div class="col-md-7">
              <h4 id="">Карта обхода  <a class="btn btn-primary">Очистить</a></h4>
              <table id="parser-tbl" class="table table-hover">
                  <thead>
                      <th>Артикул</th>
                      <th>Наименование</th>
                      
                      <th></th>
                  </thead>  
                  <tbody>
                  </tbody>
              </table> 
          </div>
          <div class="col-md-5">
              <h4 id="">Исключения <a class="btn btn-primary" data-toggle="modal" href="#add-exception-modal">Добавить</a></h4>
              <table id="exceptions-tbl" class="table table-hover">
                  <thead>
                      <th>Артикул</th>
                      <th>Action</th>
                  </thead>  
                  <tbody>
                      
                  </tbody>
              </table>
          </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Gifts Spb 2013</p>
      </footer>
    </div> <!-- /container -->

    <!-- Modal -->
     <div class="modal fade" id="add-exception-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
       <div class="modal-dialog">
         <div class="modal-content">
           <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title">Добавить исключение для парсера</h4>
           </div>
           <div class="modal-body">
               <form class="form-inline" role="form" id="exception-form">
                 <div class="form-group">
                   <label class="sr-only" for="articul-input">Артикул</label>
                   <input type="text" class="form-control" id="articul-input" placeholder="Артикул">
                 </div>
               </form>
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
             <button type="button" id="create-exception-btn" class="btn btn-primary">Добавить</button>
           </div>
         </div><!-- /.modal-content -->
       </div><!-- /.modal-dialog -->
     </div><!-- /.modal -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://codeorigin.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="parser.js"></script>
    <script src="jquery.dataTables.min.js"></script>
    
  </body>
</html>
