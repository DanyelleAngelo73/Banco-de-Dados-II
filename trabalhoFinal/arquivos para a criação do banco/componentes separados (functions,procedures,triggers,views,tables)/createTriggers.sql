/*
-------------- Insert, Update,Delete : Receitas ---------------------
*/
delimiter $
Create trigger insereReceitas after insert
on Receitas
for each row
begin 
	declare DataReceita date;
    set DataReceita = (select DataTra from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  Receitas = Receitas + (select Valor from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User) where Mes=month(DataReceita) and Ano = year(DataReceita);
end$
delimiter;

delimiter $
Create trigger updateReceitas after update
on Receitas
for each row
begin 
	declare DataReceita date;
    set DataReceita = (select DataTra from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  Receitas = Receitas - ((select Valor from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User)-(select Valor from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User)) where Mes=month(DataReceita) and Ano = year(DataReceita);
end$
delimiter;

delimiter $
Create trigger deleteReceitas before delete
on Receitas
for each row
begin 
	declare DataReceita date;
    set DataReceita = (select DataTra from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User);
    update RelatorioMensal set  Receitas = Receitas - (select Valor from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User) where Mes=month(DataReceita) and Ano = year(DataReceita);
end$
delimiter;

/*
-------------- Insert, Update,Delete : DespesasNoCartao ---------------------
*/
delimiter $
Create trigger insereDespesasNoCartao after insert
on DespesasNoCartao
for each row
begin 
	declare DataDespesa date;
    set DataDespesa = (select DataTra from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  DespesasCartao = DespesasCartao + (select Valor from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User) where Mes=month(DataDespesa) and Ano = year(DataDespesa);
end$
delimiter;


delimiter $
Create trigger updateDespesasNoCartao after update
on DespesasNoCartao
for each row
begin 
	declare DataDespesa date;
    set DataDespesa = (select DataTra from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  DespesasCartao = DespesasCartao - ((select Valor from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User) - (select Valor from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User)) where Mes=month(DataDespesa) and Ano = year(DataDespesa);
end$
delimiter;

delimiter $
Create trigger deleteDespesasNoCartao before delete
on DespesasNoCartao
for each row
begin 
	declare DataDespesa date;
    set DataDespesa = (select DataTra from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User);
   update RelatorioMensal set  DespesasCartao = DespesasCartao - (select Valor from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User) where Mes=month(DataDespesa) and Ano = year(DataDespesa);
end$
delimiter;


/*
-------------- Insert, Update,Delete : DespesasNoDinheiro ---------------------
*/
delimiter $
Create trigger insereDespesasNoDinheiro after insert
on DespesasNoDinheiro
for each row
begin
	declare DataDespesa date;
    set DataDespesa = (select DataTra from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  DespesasDinheiro = DespesasDinheiro + (select Valor from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User) where Mes=month(DataDespesa) and Ano = year(DataDespesa);
end$
delimiter;

delimiter $
Create trigger updateDespesasNoDinheiro after update
on DespesasNoDinheiro
for each row
begin
	declare DataDespesa date;
    set DataDespesa = (select DataTra from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  DespesasDinheiro = DespesasDinheiro - ((select Valor from Transacoes where Id=NEW.Id and Id_User = NEW.Id_User)-(select Valor from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User)) where Mes=month(DataDespesa) and Ano = year(DataDespesa);
end$
delimiter;

delimiter $
Create trigger deleteDespesasNoDinheiro before delete
on DespesasNoDinheiro
for each row
begin
	declare DataDespesa date;
    set DataDespesa = (select DataTra from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User);
    update RelatorioMensal set  DespesasDinheiro = DespesasDinheiro - (select Valor from Transacoes where Id=OLD.Id and Id_User = OLD.Id_User) where Mes=month(DataDespesa) and Ano = year(DataDespesa);
end$
delimiter;


/*
-------------- Insert, Update,Delete : Objetivos ---------------------
*/
delimiter $
Create trigger insereObjetivos after insert
on Objetivos
for each row
begin
	declare DataObjetivo date;
    set DataObjetivo = (select DataFim from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  ProximosObjetivos = ProximosObjetivos + (select Valor_Final from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User) where Mes<=month(DataObjetivo) and Ano<=year(DataObjetivo);
end$
delimiter;


delimiter $
Create  trigger updateObjetivos after update
on Objetivos
for each row
begin
	declare DataObjetivo date;
    set DataObjetivo = (select DataFim from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User);
    update RelatorioMensal set  ProximosObjetivos = ProximosObjetivos - ((select Valor_Final from Objetivos where Id=NEW.Id and Id_User = NEW.Id_User) - (select Valor_Final from Objetivos where Id=OLD.Id and Id_User = OLD.Id_User)) where Mes<=month(DataObjetivo) and Ano<=year(DataObjetivo);
end$
delimiter;

delimiter $
Create trigger deleteObjetivos before delete
on Objetivos
for each row
begin
	declare DataObjetivo date;
    set DataObjetivo = (select DataFim from Objetivos where Id=OLD.Id and Id_User = OLD.Id_User);
    update RelatorioMensal set  ProximosObjetivos = ProximosObjetivos - (select Valor_Final from Objetivos where Id=OLD.Id and Id_User = OLD.Id_User) where Mes<=month(DataObjetivo) and Ano<=year(DataObjetivo);
end$
delimiter;