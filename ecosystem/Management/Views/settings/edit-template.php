<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
<?= var_dump(session()->getFlashdata()); ?>
<div>
<h4><?= $template->mail_for ?> Mail Template</h4>
    <?php
        // Open form
        $attributes = ['id' => 'edit-template'];
        echo form_open(route_to('edit_mail_template', $template->id), $attributes);
    ?>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">Sender</label>
            <input type="email" class="form-control" name="sender" id="sender" value="<?= $template->mail_from ?>" placeholder="Sender Email">
            <?= $validation->showError('sender'); ?>
        </div>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">Sender Name</label>
            <input type="text" class="form-control" name="sender_name" id="sender_name" value="<?= $template->from_name ?>" placeholder="Sender Name">
            <?= $validation->showError('sender_name'); ?>
        </div>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">Subject</label>
            <textarea class="form-control" name="subject" id="subject" cols="30" rows="2"><?= $template->subject ?></textarea>
            <?= $validation->showError('subject'); ?>
        </div>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">HTML</label>
            <textarea class="form-control" name="template_html" id="template_html" cols="30" rows="2"><?= $template->template_html ?></textarea>
            <?= $validation->showError('template_html'); ?>
        </div>

        <div class="form-group">
            <label for="role" class="sr-only sr-only-focusable">Text</label>
            <textarea class="form-control" name="template_text" id="template_text" cols="30" rows="2"><?= $template->template_text ?></textarea>
            <?= $validation->showError('template_text'); ?>
        </div>


        <button type="submit" class="btn btn-purple active mg-b-10">Edit Template</button>

    </form>

</div><!-- section-wrapper -->

<?= $this->endSection() ?>