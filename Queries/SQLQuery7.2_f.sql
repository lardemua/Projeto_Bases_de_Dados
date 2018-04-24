--Ver os registos de todos os sensores tendo em conta o nome do cliente. 
SELECT DISTINCT clientes.cl_nome, registos.r_numSensor, s_tipo, r_data_hora, r_valor FROM moldes.clientes 
	INNER JOIN moldes.vendas ON vendas.v_cliente = clientes.cl_ID 
	INNER JOIN moldes.moldes ON moldes.m_venda = vendas.v_num
	INNER JOIN moldes.sensores ON sensores.s_IDMolde = moldes.m_ID
	INNER JOIN moldes.registos ON sensores.s_num = registos.r_numSensor;

--Ver os registos dos sensores do tipo x ou y
SELECT clientes.cl_nome, registos.r_numSensor, s_tipo, r_data_hora, registos.r_valor FROM moldes.clientes 
	INNER JOIN moldes.vendas ON vendas.v_cliente = clientes.cl_ID 
	INNER JOIN moldes.moldes ON moldes.m_venda = vendas.v_num 
	INNER JOIN moldes.sensores ON sensores.s_IDMolde = moldes.m_ID
	INNER JOIN moldes.registos ON sensores.s_num = registos.r_numSensor
	WHERE s_tipo = 'Dinamómetro';

--Ver quem são os trabalhadores da empresa
SELECT DISTINCT t_nome, t_nMEc, t_departamento FROM moldes.trabalhadores;

--Ver quem são os managers de cada departamento
SELECT departamentos.dep_numero AS numeroDepartamento, departamentos.dep_nome AS NomeDepartamento, trabalhadores.t_nMEc AS NumeroTrabalhador, trabalhadores.t_nome AS NomeTrabalhador FROM moldes.departamentos INNER JOIN moldes.trabalhadores ON moldes.departamentos.dep_supervisor = trabalhadores.t_nMEc;

--Ver todos os projetos com estado Terminado/UnderDevelopment/Started
SELECT projetos.p_ID, projetos.p_lider, trabalhadores.t_nome , moldes.m_descricao FROM moldes.projetos INNER JOIN moldes.moldes ON moldes.m_projeto = projetos.p_ID INNER JOIN moldes.trabalhadores ON projetos.p_lider = trabalhadores.t_nMEc WHERE moldes.m_estado = 'Em execução';

--Ver o histórico de vendas da empresa
SELECT vendas.v_num, vendas.v_data, moldes.m_montante, clientes.cl_nome, vendas.v_cliente, moldes.m_ID, moldes.m_nome FROM moldes.vendas INNER JOIN moldes.moldes ON vendas.v_num = moldes.m_venda INNER JOIN moldes.clientes ON clientes.cl_ID = vendas.v_cliente

--Verificar quanto tempo resta da garantia de um molde
SELECT moldes.m_ID, moldes.m_nome, DATEDIFF(DAY, GETDATE(), m_garantia) AS GarantiaRestante FROM moldes.moldes WHERE moldes.m_ID = 9001; --00 está na vez do ID que se quer

--Verificar qual foi o vendedor que fez mais dinheiro
SELECT t_nMEc, sum(moldes.m_montante) AS montanteTotal FROM moldes.vendas INNER JOIN moldes.trabalhadores ON v_vendedor = t_nMEc INNER JOIN moldes.moldes ON vendas.v_num = moldes.m_venda  WHERE m_montante IS NOT NULL GROUP BY t_nMEc ORDER BY montanteTotal DESC

--Verificar a venda que fez mais dinheiro
SELECT  v_num, sum(moldes.m_montante) AS montanteTotal FROM moldes.vendas INNER JOIN  moldes.moldes ON vendas.v_num = moldes.m_venda  WHERE m_montante IS NOT NULL GROUP BY v_num ORDER BY montanteTotal DESC

--Verificar quais os moldes sem sensores
SELECT * FROM moldes.moldes LEFT JOIN moldes.sensores ON moldes.m_ID = sensores.s_IDMolde WHERE s_IDMolde IS NULL

--Verificar quais os moldes com sensores
SELECT DISTINCT * FROM moldes.moldes LEFT JOIN moldes.sensores ON moldes.m_ID = sensores.s_IDMolde WHERE s_IDMolde IS NOT NULL

--Verificar qual o líder de cada projetam
SELECT * FROM moldes.projetos INNER JOIN moldes.trabalhadores ON projetos.p_lider = trabalhadores.t_nMEc

--Papéis dos projetistas em diferentes projetos
SELECT p_ID, p_funcao, t_nome, t_departamento FROM moldes.projetos INNER JOIN moldes.projetam ON projetos.p_ID = projetam.p_projeto INNER JOIN moldes.trabalhadores ON projetam.p_projetista = trabalhadores.t_nMEc
