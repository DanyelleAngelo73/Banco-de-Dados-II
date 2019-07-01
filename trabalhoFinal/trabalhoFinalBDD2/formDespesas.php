<?php
  $buscaCategorias = $query->sql_query("SELECT * FROM Categoria where Id_User = {$_SESSION['Id']}");
?>
<div class='control-group'>
  <label class='control-label' for='descricao'>Descrição: </label>
  <div class='controls'>
    <input type='text' name='descricao' required placeholder='Descrição'>
  </div>
</div>

<div class='control-group'>
  <label class='control-label' for='situacao'>Situação</label>
  <div class='controls'>
    <select name='situacao' required placeholder='Situação'>
      <option value='A pagar'>A pagar</option>
      <option value='Atrasada'>Atrasada</option>
    </select>
  </div>
</div>

<div class='control-group'>
  <label class='control-label' for='categoria'>Categoria</label>
  <div class='controls'>
    <select name='categoria' required placeholder='Categoria'>
      <?php
        if(mysqli_num_rows($buscaCategorias)>0){
          while($categoria=$buscaCategorias->fetch_assoc()){
            echo "<option value=".$categoria['Id'].">".$categoria['Nome']."</option>";
          }
        }
?>
   </select>
</div>
</div>

<div class='control-group'>
  <label class='control-label' for='data'>Data de Vencimento: </label>
  <div class='controls'>
    <input type='text' name='dataTra' required placeholder='YYYY-MM-DD'>
  </div>
</div>

<div class='control-group'>
  <label class='control-label' for='periodicidade'>Periodicidade</label>
  <div class='controls'>
    <select name='periodicidade' required placeholder='Periodicidade'>
      <option value='Semanal'>Nenhuma</option>
      <option value='Semanal'>Semanal</option>
      <option value='Quinzenal'>Quinzenal</option>
      <option value='Mensal'>Mensal</option>
      <option value='Anual'>Anual</option>
    </select>
  </div>
</div>

<div class='control-group'>
  <label class='control-label' for='valor'>Valor: R$ </label>
  <div class='controls'>
    <input type='text' name='valor' required placeholder='Valor'>
  </div>
</div>

<div class='control-group'>
  <label class='control-label' for='juros'>Juros ao mês: %</label>
  <div class='controls'>
    <input type='text' name='juros' required placeholder='juros'>
  </div>
</div>
