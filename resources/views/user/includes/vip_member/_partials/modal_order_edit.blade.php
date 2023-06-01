<div class="modal fade" id="orderEditModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Order Edit</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

            	<div class="from-wrap-page">
            		<div class="form-group">
							      <label for="">Status</label>
							      <select name="status" id="" class="selectMd-2">
							          <option value="0">Inactive</option>
							          <option value="1">Pending</option>
							          <option value="2">Cancelled</option>
							          <option value="3">Completed</option>
							      </select>
							  </div>
            	</div>
            	<input type="hidden" name="order_id" value="" />

            	<a href="javascript:;" class="commonBtn orderEditModal_submit">Update</a>

            </div>
        </div>
    </div>
</div>