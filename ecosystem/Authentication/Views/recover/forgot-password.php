<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <?= var_dump(session()->getFlashdata()); ?>
    
    <?php
        // Open form
        $attributes = ['id' => 'forgot-password', 'autocomplete' => 'off'];
        echo form_open(route_to('recover_password'), $attributes);
    ?>

        <div class="form-group">
            <input type="text" class="form-control" name="email" id="email" placeholder="Enter email">
            <?= $validation->showError('email'); ?>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-signin">Reset</button>
        <p class="mg-b-0">Remember Password? <a href="<?= base_url('/login') ?>">Cancel Reset</a></p>
        
    </form>
<?= $this->endSection() ?>