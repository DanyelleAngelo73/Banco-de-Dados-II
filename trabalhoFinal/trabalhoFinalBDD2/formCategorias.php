<div class="control-group">
  <label class="control-label" for="nome">Nome: </label>
  <div class="controls">
    <input type="text" name="nome" required placeholder="Nome">
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="cor">Cor</label>
  <div class="controls">
    <input type="color"  name="cor" list="cores" value="#00c6c9" style="height:40px;">
    <datalist id="cores">
      <option value="#00c6c9">Azul</option>
      <option value="#FF0000">Vermelho</option>
      <option value="#FFA500">Laranja</option>
      <option value="#FFFF00">Amarelo</option>
      <option value="#a6ff05">Verde</option>
      <option value="#4B0082">Indigo</option>
      <option value="##733e76">Violeta</option>
    </datalist>
  </div>
</div>

<div class="control-group">
  <button type="submit" class="btn btn-inverse" name="concluir">Concluir</button>
  <button type="reset" class="btn btn-inverse" name="Limpar">Limpar</button>
</div>
