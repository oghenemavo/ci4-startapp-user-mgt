<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>

<div>

        <?php
            // Open form
            $attributes = ['id' => 'set-client'];
            echo form_open(route_to('set_client'), $attributes);
        ?>
        <button type="submit">set</button>

        <table id="mail-clients" class="table">
            <thead>
                <tr>
                    <th>s/no</th>
                    <th>Mail Client</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                foreach ($clients as $mail) : ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $mail->client ?></td>
                        <td>
                            <input type="radio" name="client" id="client" 
                            value="<?= $mail->id ?>" <?= $mail->is_active == '1' ? 'checked' : '' ?> >
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </form>

</div><!-- section-wrapper -->


<?= $this->endSection() ?>