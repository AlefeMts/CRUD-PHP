
<?php
include_once 'conexao.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id = (isset($_POST["id"]) && $_POST["id"] !=null) ? $_POST["id"]:"";
    $cliente = (isset($_POST["cliente"]) && $_POST["cliente"] !=null) ? $_POST["cliente"]:"";
    $numContainer = (isset($_POST["numContainer"]) && $_POST["cliente"] !=null) ? $_POST["numContainer"]:"";
    $tipo = (isset($_POST["tipo"]) && $_POST["tipo"] !=null) ? $_POST["tipo"]:"";
    $status = (isset($_POST["status"]) && $_POST["status"] !=null) ? $_POST["status"]:"";
    $categoria = (isset($_POST["categoria"]) && $_POST["categoria"] !=null) ? $_POST["categoria"]:"";
    $idMovimentacao = (isset($_POST["idMovimentacao"]) && $_POST["idMovimentacao"] !=null) ? $_POST["idMovimentacao"]:"";
    

    //INFORMAÇÃO MOVIMENTAÇÃO

    $tipoMovimentacao = (isset($_POST["tipoMovimentacao"]) && $_POST['tipoMovimentacao'] !=null) ? $_POST["tipoMovimentacao"]:"";
    $dataIni = (isset($_POST["dataIni"]) && $_POST["dataIni"] !=null) ? $_POST["dataIni"]:"";
    $horaIni = (isset($_POST["horaIni"]) && $_POST["horaIni"] !=null) ? $_POST["horaIni"]:"";
    $dataFim = (isset($_POST["dataFim"]) && $_POST["dataFim"] !=null) ? $_POST["dataFim"]:"";
    $horaFim = (isset($_POST["horaFim"]) && $_POST["horaFim"] !=null) ? $_POST["horaFim"]:"";

}else if(!isset($id)){
    $id = (isset($_GET["id"]) && $_GET["id"] !=null) ? $_GET["id"]:"";
    $cliente = null;
    $numContainer = null;
    $tipo = null;
    $status = null;
    $categoria = null;
    $idMovimentacao = null;
    $tipoMovimentacao = null;
    $dataIni = null;
    $horaIni = null;
    $dataFim = null;
    $horaFim = null;


}

//Bloco if que salva os dados no Banco, atua como Create e UPDATE
if(isset($_REQUEST["act"]) && $_REQUEST["act"] == "save" && $cliente !=""){
    try{
        if($id != ""){
            $query = $conn->prepare("UPDATE container SET cliente=?, numContainer=?, tipo=?, status=?, categoria=? WHERE id=?");
            $query->bindParam(6,$id);
        }else{
            $query = $conn->prepare("INSERT INTO container(cliente, numContainer, tipo, status, categoria) VALUES(?,?,?,?,?)");
            $queryMov = $conn->prepare("INSERT INTO movimentacao(tipoMovimentacao, dataIni, horaIni, dataFim, horaFim) VALUES(?,?,?,?,?)");
        }   
       
        $query->bindParam(1,$cliente);
        $query->bindParam(2,$numContainer);
        $query->bindParam(3,$tipo);
        $query->bindParam(4,$status);
        $query->bindParam(5,$categoria);


        $queryMov->bindParam(1, $tipoMovimentacao);
        $queryMov->bindParam(2, $dataIni);
        $queryMov->bindParam(3, $horaIni);
        $queryMov->bindParam(4, $dataFim);
        $queryMov->bindParam(5, $horaFim);
        

        if($query->execute() && $queryMov->execute()){
            if($query->rowCount() > 0 && $queryMov->rowCount() > 0){
                echo "Dados cadastrados com sucesso!!";
                $id = null;
                $cliente = null;
                $numContainer = null;
                $tipo = null;
                $status = null;
                $categoria = null;
    
            }else{
                echo "Erro ao tentar efetivar o cadastro!!";
            }
        }else{
            throw new PDOException("ERRO: não foi possível executar a declaração SQL");
        }

        

    }catch(PDOException $erro){
        echo "ERRO".$erro->getMessage();
    }
}

//Bloco if que recupera as informações no formulário
if(isset($_REQUEST["act"]) && $_REQUEST["act"] == "upd" && $id != ""){
    try{
        $query = $conn->prepare("SELECT * FROM container WHERE id=?");
        $query->bindParam(1, $id, PDO::PARAM_INT);

        if($query->execute()){
            $rs = $query->fetch(PDO::FETCH_OBJ);
            $id = $rs->id;
            $cliente = $rs->cliente;
            $numContainer = $rs->numContainer;
            $tipo = $rs->tipo;
            $status = $rs->status;
            $categoria = $rs->categoria;


        }else{
            throw new PDOException("ERRO: não foi possível executar a declaração SQL");
        }
    }catch(PDOException $erro){
        echo "ERRO".$erro->getMessage();
    }

}
// Bloco Delete

if(isset($_REQUEST["act"]) && $_REQUEST["act"] == "del" && $id !=null){
    try{
        $query = $conn->prepare("DELETE FROM container WHERE id=?");
        $query->bindParam(1, $id, PDO::PARAM_INT);

        if($query->execute()){
            echo "Registro excluido com sucesso!!";
            $id = null;
        }else{
            echo "ERRO: Não foi possível executar a declaração SQL!";
        }
    }catch(PDOException $erro){
        echo "ERRO".$erro->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8"/>
<title>Formulário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<!-- Conteúdo -->

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand">CONTAINER</a>
    <form class="d-flex">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav>
<br>
<div class="container">
    <div class="bg-light p-5 rounded">
        <h1>Cadastro de Container</h1>
        <br>
    <form method="POST" action="?act=save"act class="row g-3">
    <div class="col-md-6">  
    <input type="hidden" class="form-control" name="id" id="id">
  </div>
     <div class="col-md-12">
    <label for="inputEmail4" class="form-label">Nome Cliente</label>
    <input type="text" class="form-control" name="cliente" id="cliente"<?php 
    if(isset($cliente) && $cliente != null || $cliente !=""){
        echo "value=\"{$cliente}\"";
    }
    ?>>
  </div>
  <div class="col-md-6">
    <label for="inputPassword4" class="form-label">Número de container</label>
    <input type="text" class="form-control" name="numContainer" id="numContainer" <?php 
    if(isset($numContainer) && $numContainer != null || $numContainer !=""){
        echo "value=\"{$numContainer}\"";
    }
    ?>>
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Tipo</label>
    <select id="inputState" class="form-select" name="tipo" <?php 
    if(isset($tipo) && $tipo != null || $tipo !=""){
        echo "value=\"{$tipo}\"";
    }
    ?>>
      <option selected >20</option>
      <option>40</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Status</label>
    <select id="inputState" class="form-select" name="status"<?php 
    if(isset($status) && $status != null || $status !=""){
        echo "value=\"{$status}\"";
    }
    ?>>
      <option selected>Vazio</option>
      <option>Cheio</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="inputState" class="form-label">Categoria</label>
    <select id="inputState" class="form-select" name="categoria" <?php 
    if(isset($categoria) && $categoria != null || $categoria !=""){
        echo "value=\"{$categoria}\"";
    }
    ?>>
      <option selected>Importação</option>
      <option>Exportação</option>
    </select>
  </div>
  <div class="col-md-6">  
    <input type="hidden" class="form-control" name="idMovimentacao" id="idMovimentacao">
  </div>
  <br><br>
  <h1>Movimentações</h1>
  <div class="col-md-6">  
    <input type="hidden" class="form-control" name="id_mov" id="id_mov">
  </div>
    <br>
  <div class="col-md-12">
    <label for="inputState" class="form-label">Tipo de Movimentação</label>
    <select id="inputState" class="form-select" name="tipoMovimentacao">
      <option selected>Embarque</option>
      <option>Descarga</option>
      <option>Gate-in</option>
      <option>Gate- out</option>
      <option>Reposicionamento</option>
      <option>Pesagem</option>
      <option>Scanner</option>
    </select>
  </div>
    <div class="col-md-2">
    <label for="inputEmail4" class="form-label">Data Inicio</label>
    <input type="date" class="form-control" name="dataIni" id="cliente">
  </div>
  <div class="col-md-2">
    <label for="inputEmail4" class="form-label">Hora Inicio</label>
    <input type="time" class="form-control" name="horaIni" id="cliente">
  </div>
  <div class="col-md-2">
    <label for="inputEmail4" class="form-label">Data Fim</label>
    <input type="date" class="form-control" name="dataFim" id="cliente">
  </div>
  <div class="col-md-2">
    <label for="inputEmail4" class="form-label">Hora Fim</label>
    <input type="time" class="form-control" name="horaFim" id="cliente">
  </div>
    <br>
  <div class="col-12">
    <button type="submit" class="btn btn-primary" value="Gravar">Gravar</button>
  </div>
  
</form>
<br>
<table class="table table-striped table-hover">
  <tr>
      <td>Nome</td>
      <td>Tipo da Movimentação</td>
  </tr>
  <h2>Resultado</h2>

  <?php
            try{
                $query = $conn->prepare("SELECT * FROM container");
                if($query->execute()){
                    while($rs = $query->fetch(PDO::FETCH_OBJ)){
                        echo "<tr>";
                        echo "<td>".$rs->cliente."</td><td><a href=\"?act=del&id=".$rs->id."\">[Excluir]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
                        "<a href=\"?act=upd&id=".$rs->id."\">[Alterar]</a></td";
                        echo "</tr>";
                    }
                }else{
                    echo "ERRO: Não foi possível recuperar os dados do banco!";
                }

            }catch(PDOException $erro){
        echo "ERRO".$erro->getMessage();
    }
        ?>
</table>
        
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>