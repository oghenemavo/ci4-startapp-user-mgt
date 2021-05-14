<?= $this->extend('Ecosystem\Authentication\Views\layouts\app') ?>

<?= $this->section('content') ?>
    <h4>Provide code sent to your Email</h4>

    <?php
        // Open form
        $attributes = ['id' => 'activate', 'autocomplete' => 'off'];
        echo form_open(route_to('account_verify', $verification), $attributes);
    ?>
        <div id="form-info" class="alert alert-danger mb-2 text-center" role="alert" style="display:none;">
        
        </div>
        <div class="form-group">
            <input type="text" id="code" name="code" class="form-control" placeholder="Code" autofocus="true" required>
            <?= $validation->showError('code'); ?>
            <div id="invalid-code" class="invalid-feedback"></div>
        </div>

        <input type="hidden" name="verifier" id="verifier" value="<?= $verification; ?>">
        
        <button class="btn btn-lg btn-block btn-login" id="submit" type="submit">Confirm</button>
        
        <hr>
        <div class="text-center" style="font-size: 15px;">
            Didn't get an activation code? &nbsp;<a href="<?= route_to('resend_verification', $verification); ?>" id="resend" style="color:#7F6610;font-weight:lighter;">Resend</a>
        </div>
    </form>

<?= $this->endSection() ?>