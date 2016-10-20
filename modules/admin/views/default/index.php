<?php
use zbsoft\helpers\Url;

?>
<?= $this->render("/layouts/menu-nav") ?>
<div class="table-responsive">
    <div class="table-nav">
        <button type="button" class="btn btn-success">Add</button>
        <button type="button" class="btn btn-warning logout-btn" id="logout-btn"
                data-url="<?= Url::toRoute("default/logout") ?>" data-turn="<?= Url::toRoute("public/login") ?>">Logout
        </button>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>Page</th>
            <th>Visits</th>
            <th>% New Visits</th>
            <th>Revenue</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>/index.html</td>
            <td>1265</td>
            <td>32.3%</td>
            <td>$321.33</td>
        </tr>
        <tr>
            <td>/about.html</td>
            <td>261</td>
            <td>33.3%</td>
            <td>$234.12</td>
        </tr>
        <tr>
            <td>/sales.html</td>
            <td>665</td>
            <td>21.3%</td>
            <td>$16.34</td>
        </tr>
        <tr>
            <td>/blog.html</td>
            <td>9516</td>
            <td>89.3%</td>
            <td>$1644.43</td>
        </tr>
        <tr>
            <td>/404.html</td>
            <td>23</td>
            <td>34.3%</td>
            <td>$23.52</td>
        </tr>
        <tr>
            <td>/services.html</td>
            <td>421</td>
            <td>60.3%</td>
            <td>$724.32</td>
        </tr>
        <tr>
            <td>/blog/post.html</td>
            <td>1233</td>
            <td>93.2%</td>
            <td>$126.34</td>
        </tr>
        </tbody>
    </table>
    <nav>
        <ul class="pagination">
            <li>
                <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li><a href="#">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li><a href="#">5</a></li>
            <li>
                <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
</div>