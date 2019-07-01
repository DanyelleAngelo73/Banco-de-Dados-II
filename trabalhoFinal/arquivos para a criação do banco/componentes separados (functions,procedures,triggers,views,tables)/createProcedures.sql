delimiter $
create procedure inicializaRelatorio(cod int, mes numeric(2), ano numeric(4))
begin
	declare receitas numeric(5,2) default '0.0';
	declare dinheiro numeric(5,2) default '0.0';
    declare cartao numeric(5,2) default '0.0';
    declare valorObjetivos numeric(5,2) default '0.0';
    set receitas = (select sum(Valor) from Transacoes t inner join Receitas r on t.Id=r.Id where (mes = month(DataTra) and ano = year(DataTra)) and Id_User = cod);
	set dinheiro =  (select sum(Valor) from Transacoes t inner join DespesasNoDinheiro d on t.Id=d.Id where (mes = month(DataTra) and ano = year(DataTra)) and Id_User = cod);
	set cartao =  (select sum(Valor) from Transacoes t inner join DespesasNoCartao d on t.Id=d.Id where (mes = month(DataTra) and ano = year(DataTra)) and Id_User = cod);
	set valorObjetivos =  (select sum(Valor_Final) from Objetivos where (mes <= month(DataFim) and ano <= year(DataFim)) and Id_User = cod);
	
    update RelatorioMensal set Receitas = receitas, DespesasCartao=cartao, DespesasDinheiro = dinheiro, ProximosObjetivos= valorObjetivos where (mes = Mes and ano = Ano and Id_User = cod);
end $
delimiter;

call inicializaRelatorio(4,2,2);
