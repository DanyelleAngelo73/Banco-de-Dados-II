CREATE VIEW visualizaReceitas AS
    SELECT 
        c.Id AS IdCategoria,
        Nome AS NomeCategoria,
        Cor,
        t.Id AS IdTransacao,
        t.Id_User,
        Valor,
        Situacao,
        Peridiocidade,
        Descricao,
        DataTra
    FROM
        Receitas r
            INNER JOIN
        Transacoes t ON t.Id = r.Id
            INNER JOIN
        Categoria c ON IdCategoria = c.Id WITH CHECK OPTION;


CREATE VIEW visualizaDespesasDinheiro AS
    SELECT 
        c.Id AS IdCategoria,
        Nome AS NomeCategoria,
        Cor,
        t.Id AS IdTransacao,
        t.Id_User,
        Valor,
        Situacao,
        Peridiocidade,
        Descricao,
        DataTra,
        Juros
    FROM
        DespesasNoDinheiro d
            INNER JOIN
        Transacoes t ON t.Id = d.Id
            INNER JOIN
        Categoria c ON IdCategoria = c.Id WITH CHECK OPTION;
        
CREATE  VIEW visualizaDespesasCartao AS
    SELECT 
		d.NumeroCartao,
        c.Id AS IdCategoria,
        c.Nome AS NomeCategoria,
        Cor,
        t.Id AS IdTransacao,
        t.Id_User,
        Valor,
        Situacao,
        Peridiocidade,
        Descricao,
        DataTra,
        Juros,
        ca.Id as IdCartao,
        ca.Nome as NomeCartao
    FROM
		DespesasNoCartao d
            INNER JOIN
        Transacoes t ON t.Id = d.Id
            INNER JOIN
        Categoria c ON IdCategoria = c.Id 
			INNER JOIN Cartao ca on NumeroCartao = ca.Id WITH CHECK OPTION;
		