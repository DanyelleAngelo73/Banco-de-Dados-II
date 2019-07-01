<?php
class Connection{
  public $ultimoId = 0;
  public $status = 0;

  public function conectaBD(){
    $conecta = new mysqli("localhost", "root", " ", "controleDeFinancas");//local

    return $conecta;
  }

  public function desconectBD($fechaConexao){
    $fechaConexao->close();
  }

  public function sql_query($operacao){
    $conexao = $this->conectaBD();
    $resposta= $conexao->query($operacao);
    $this->ultimoId = $conexao->insert_id;//Id do último item inserito
    $this->status = $conexao->affected_rows;//o que aconteceu com minha operação?
    $this->desconectBD($conexao);
    return $resposta;
  }



}

?>
