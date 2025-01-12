

<div class="container">
    <div class="row" id="formParent">
        <div class="col-xs-12 col-sm-12">
            <?php echo $this->session->flashdata('message'); ?>
            <div id="errSuccess"></div>
        </div>

        <div class="col-xs-12 col-sm-12">

            <div class="col-xs-12 col-sm-3">
                <div class="form-group">
                    <?php echo form_dropdown('id_subsidi', $subsidi, $this->input->post('id_subsidi', TRUE), 'class="select-all" id="id_subsidi"'); ?>
                </div>
            </div>

            <div class="col-xs-12 col-sm-2">
                <button class="btn btn-default" aria-controls="mainTbl" type="button" title="btnDetail" style="margin-right:10px; margin-bottom:20px;" id="btnDetail"><span>2021</span></button>
            </div>
        </div>

        <div id="container"></div>

    </div>
</div><!-- container -->

<?php $this->load->view("dashboard_ekonomi/form_subsidi/modal.php") ?>
<?php $this->load->view("dashboard_ekonomi/form_subsidi/js.php") ?>