delimiter $
create function InicioDeMes(IdUserR int) returns int
begin
	insert into RelatorioMensal values(IdUserR,month(CURDATE()),year(CURDATE()),0.0,0.0,0.0,0.0);
	return null;
end$
delimiter;
select * from RelatorioMensal;

delimiter $
create function ReceitasPorCategoria(IdUserR int, categoria int) returns int
begin
	return (
	select sum(Valor) from Receitas r inner join Transacoes t on r.Id = t.Id  where t.Id_User = IdUserR and t.IdCategoria = categoria);
end$
delimiter;


delimiter $
create function DespesasCartaoPorCategoria(IdUserR int, categoria int) returns int
begin
	return (
	select sum(Valor) from DespesasNoCartao d inner join Transacoes t on d.Id = t.Id where t.Id_User = IdUser and t.IdCategoria = categoria);
end$
delimiter;

delimiter $
create function DespesasDinheiroPorCategoria(IdUserR int, categoria int) returns int
begin
	return (
	select sum(Valor) from DespesasNoDinheiro d inner join Transacoes t on d.Id = t.Id where t.Id_User = IdUserR  and t.IdCategoria = categoria);
end$
delimiter;