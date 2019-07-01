<?php
  $buscaCategorias = $query->sql_query("SELECT * FROM Categoria where Id_User = {$_SESSION['Id']}");
?>

<div class="control-group">
  <label class="control-label" for="descricao">Descrição: </label>
  <div class="controls">
    <input type="text" name="descricao" required placeholder="Descrição">
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="situacao">Situação</label>
  <div class="controls">
    <select name="situacao" required placeholder="Situação">
      <option value="Recebida">Recebida</option>
      <option value="A receber">A receber</option>
    </select>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="categoria">Categoria</label>
  <div class="controls">
    <select name="categoria" required placeholder="Categoria">
      <option value=""></option>
      <?php
        if(mysqli_num_rows($buscaCategorias)>0){
          while($linha=$buscaCategorias->fetch_assoc()){
            echo "<option value='".$linha['Id']."'>".$linha['Nome']."</option>";
          }
        }
      ?>
    </select>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="data">Data de Recebimento: </label>
  <div class="controls">
    <input type="text" name="dataTra" required placeholder="YYYY-MM-DD">
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="periodicidade">Periodicidade</label>
  <div class="controls">
    <select name="periodicidade" required placeholder="Periodicidade">
      <option value="Semanal">Nenhuma</option>
      <option value="Semanal">Semanal</option>
      <option value="Quinzenal">Quinzenal</option>
      <option value="Mensal">Mensal</option>
      <option value="Anual">Anual</option>
    </select>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="valor">Valor: R$ </label>
  <div class="controls">
    <input type="text" name="valor" required placeholder="Valor">
  </div>
</div>

<div class="control-group">
  <button type="submit" class="btn btn-inverse" name="concluir">Concluir</button>
  <button type="reset" class="btn btn-inverse" name="Limpar">Limpar</button>
</div>
