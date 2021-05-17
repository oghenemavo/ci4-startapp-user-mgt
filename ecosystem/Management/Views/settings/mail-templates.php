<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<div>

    <table id="mail-clients" class="table">
        <thead>
            <tr>
                <th>s/no</th>
                <th>Sender</th>
                <th>Template For</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($templates as $data) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $data->mail_from ?></td>
                    <td><?= $data->mail_for ?></td>
                    <td>
                        <a href="<?= base_url(route_to('show_mail_template', $data->id)) ?>">view</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div><!-- section-wrapper -->


<?= $this->endSection() ?>