Create table Usuario(
	Id int auto_increment,
	Email varchar(30) not null,
    Nome varchar(30) not null,
    Senha varchar(16) not null,
    CPF numeric(11),
    Telefone numeric(11),
    Avatar varchar(60),
    Profissao varchar(30),
    CEP numeric(8),
    Endereco varchar(30),
    Idioma varchar(16),
    Moeda varchar(16),
    Data_Nascimento date,
    constraint primary key (Id,Email)
);
ALTER TABLE Usuario MODIFY Senha varchar(32);
Create table Objetivos(
	Id int auto_increment,
    Id_User int not null,
    Nome varchar(30) not null,
    Descricao varchar(200),
    Valor_Inicial numeric(5,2) default '0.0' check(Valor_Inicial>=0),
    Valor_Final numeric(5,2) default '0.0' check(Valor_Final>=0),
    DataFim date,
    Cor varchar(6),
    constraint primary key(Id,Id_User),
    constraint foreign key fk_user (Id_User) references Usuario(Id)
);
ALTER TABLE Objetivos MODIFY Cor varchar(7);#precisamos de espaço para '#'
ALTER TABLE Objetivos MODIFY Valor_Inicial  numeric(20,2);
ALTER TABLE Objetivos MODIFY Valor_Final  numeric(20,2);
Create table Categoria(
	Id int auto_increment,
    Id_User int not null,
    Nome varchar(30) not null,
    Cor varchar(6),
	constraint primary key(Id,Id_User),
	constraint foreign key fk_user (Id_User) references Usuario(Id)
);
ALTER TABLE Categoria MODIFY Cor varchar(7);#precisamos de espaço para '#'
Create table Cartao(
	Id int auto_increment,
    Id_User int not null,
    Nome varchar(30) not null,
	Bandeira varchar(30),
    Limite numeric(5,2) default '0.0' check(Limite>=0),
    DataFechamento date,
	DataPagamento date check(DataPagamento > DataFechamento),
    constraint primary key(Id,Id_User),
    constraint foreign key fk_user (Id_User) references Usuario(Id)
); 
ALTER TABLE Cartao MODIFY Limite numeric(20,2);
ALTER TABLE Cartao MODIFY DataFechamento numeric(2,0);#pegaremos só o dia de fechamento
ALTER TABLE Cartao MODIFY DataPagamento  numeric(2,0);#pegaremo só o dia de pagamento
Create table Transacoes(
	Id int auto_increment,
    Id_User int not null,
    Valor numeric(5,2) default '0.0' check(Valor >=0),
    Situacao varchar(16) not null,
    Peridiocidade varchar(16) default 'Nenhuma',
    Descricao varchar(200),
    DataTra date,
    constraint primary key(Id,Id_User),
    constraint foreign key fk_user (Id_User) references Usuario(Id)
);
ALTER TABLE Transacoes MODIFY Valor numeric(20,2);
ALTER TABLE Transacoes ADD IdCategoria int;
ALTER TABLE Transacoes ADD constraint foreign key fk_transacoesCategoria (IdCategoria) references Categoria(Id);
Create  table Receitas(
	Id int not null,
    Id_User int not null,
    constraint primary key(Id,Id_User),
    constraint foreign key fk_receitas (Id,Id_User) references Transacoes(Id,Id_User)
);

Create table DespesasNoCartao(
	Id int not null,
    Id_User int not null,
    Juros numeric(4,2) default '0.0',
    NumeroCartao int not null,
    constraint primary key(Id,Id_User),
    constraint foreign key fk_despesas (Id,Id_User) references Transacoes(Id,Id_User),
    constraint foreign key fk_despesasCartao (NumeroCartao) references Cartao(Id)
);

Create  table DespesasNoDinheiro(
	Id int not null,
    Id_User int not null,
    Juros numeric(4,2) default '0.0',
    constraint primary key(Id,Id_User),
    constraint foreign key fk_despesas (Id,Id_User) references Transacoes(Id,Id_User)
);

Create table RelatorioMensal(
	Id_User int not null,
	Mes numeric(2) not null,
    Ano numeric(4) not null,
    Receitas numeric(5,2) default '0.0',
    DespesasDinheiro numeric(5,2) default '0.0',
    DespesasCartao numeric(5,2) default '0.0',
    ProximosObjetivos numeric(5,2) default '0.0',
    constraint primary key(Id_User,Mes,Ano),
	constraint foreign key fk_user (Id_User) references Usuario(Id)
);
ALTER TABLE RelatorioMensal MODIFY Receitas  numeric(30,2);
ALTER TABLE RelatorioMensal MODIFY DespesasCartao numeric(30,2);
ALTER TABLE RelatorioMensal MODIFY DespesasDinheiro numeric(30,2);
ALTER TABLE RelatorioMensal MODIFY ProximosObjetivos numeric(30,2);