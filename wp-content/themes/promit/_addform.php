<section id="insert-promise">

    <form action="" method="post">

        <p>Introduceti va rog datele promisiunii in cimpurile de mai jos</p>

        <label><h3>Titlu Promisiunii *</h3>
            <input required="required" type="text" name="" id="title" >
        </label>

        <div class="col">
            <h3>Descriere</h3>
            <textarea></textarea>
        </div>
        <div class="col">
            <section>
                <label><h3>Cine a dat Promisiunea? *</h3>
                    <input required="required" type="text" name="" id="who" >
                </label>
            </section>

            <section>
                <label>
                    <h3>Sursa *</h3>
                    <input required="required" type="text" name="" id="source" >
                </label>
            </section>

            <section>
                <h3>Perioda</h3>
                <input class="date" placeholder="De la" type="text" name="" id="dela" > <input class="date" placeholder="Pina la" type="text" name="" id="pinala" >
            </section>

        </div>

        <div class="clearfix"></div>

        <div class="btn-outter"><!--<button type="submit" class="btn">adauga promisiune</button>--><img class="button" src="<?= get_template_directory_uri() ?>/images/adauga-btn.png"></div>

    </form>

</section>