<?php
//importando arquivo functions.php de dentro da pasta funcs
include 'funcs/functions.php';

//criando conexão por meio do PDO
$pdo = pdo_connect_mysql();
//definindo página de início da paginação de contatos
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
//definindo limite de 5 contatos por página
$records_per_page = 5;

//seleção da lista de contatos do banco de dados
$stmt = $pdo->prepare('SELECT * FROM contacts ORDER BY id DESC LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$num_contacts = $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();

?>

<!-- Definindo template de Lista de Contatos -->
<?=template_header('Read')?>

<div class="content read">
	<h2>Lista de Contatos</h2>
	<a href="create.php" class="create-contact">Adicionar contato</a>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Nome</td>
                <td>E-mail</td>
                <td>Telefone</td>
                <td>Profissão</td>
                <td>Criado em</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <!-- listando os contatos com um laço FOREACH -->
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <td><?=$contact['name']?></td>
                <td><?=$contact['email']?></td>
                <td><?=$contact['phone']?></td>
                <td><?=$contact['title']?></td>
                <td><?=$contact['created']?></td>
                <td class="actions">
                    <a href="update.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="delete.php?id=<?=$contact['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- sistema de paginação da lista de contatos -->
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_contacts): ?>
		<a href="read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>