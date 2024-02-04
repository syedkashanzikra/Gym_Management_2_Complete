<div class="ttm-pricing-plan" data-plan="{{ json_encode($plan) }}">
    <div class="ttm-p_table-head">
        <div class="ttm-p_table-icon">
            <img style="width: 110px" src="{{ asset('images/logo-alt.png') }}">
        </div>
        <div class="ttm-p_table-title">
            <h3>{{ $label }}
                @if ($monthly_fee > 0)
                    <span>*</span>
                @endif
            </h3>
        </div>
        <div class="ttm-p_table-box-desc"></div>
    </div>
    <ul class="ttm-list ttm-list-style-icon ttm-list-icon-color-darkgrey pt-15 pb-15">
        @foreach ($feature_lines as $feature)
            <li>
                <span class="ttm-list-li-content text-left">{!! $feature !!}</span>
            </li>
        @endforeach
    </ul>
    <div class="ttm-p_table-amount @if ($monthly_fee <= 0) visibility-hidden @endif">
        <span class="cur_symbol">{{ $cur_symbol }}</span>
        <span class="ttm-ptablebox-price month">{{ $monthly_fee }}</span>
        <span style="display: none" class="ttm-ptablebox-price year">{{ $yearly_fee }}</span>
        <span class="pac_frequency month">/Per Month</span>
        <span style="display: none" class="pac_frequency year">/Per Year</span>
    </div>
    <div class="ttm-p_table-footer">
        <form action="{{ member_url('sign-up') }}" method="get">
            <input type="hidden" name="plan" value="{{ $id }}">
            <input class="plan-interval" type="hidden" name="interval" value="month">
            <button
                class="ttm-btn ttm-btn-size-md ttm-btn-shape-square ttm-btn-style-fill ttm-icon-btn-right ttm-btn-color-skincolor text-center margin_top30 z-index-1">Signup</button>
        </form>
    </div>
</div>
