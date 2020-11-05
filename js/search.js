        jQuery(function($){ 

            class Search {
                constructor() {
                    this.resultsDiv = $("#search-overlay__results");
                    this.resultsHover = $(".search-hover");
                    this.openButton = $(".my-search");
                    this.closeButton = $(".search-overlay__close");
                    this.searchOverlay = $(".search-overlay");
                    this.searchField = $("#search-term");
                    this.formSubmit = $(".submit-button");
                    this.events();
                    this.previousValue;
                    this.typingTimer;
                }
            
            //events
            events() {
                this.openButton.on("click", this.openOverlay.bind(this));
                this.closeButton.on("click", this.closeOverlay.bind(this));
                this.searchField.on("keyup", this.typingLogic.bind(this));
                this.formSubmit.on("click", this.getResults.bind(this), function(e){
                    e.preventDefault();
                    });
                this.formSubmit.on("click", this.getResults.bind(this));
                
                
            }
                
            // 3. Methods
            

            typingLogic() {
                if (this.searchField.val() != this.previousValue) {
                    clearTimeout(this.typingTimer);
                    
                    if (this.searchField.val()) {
                        this.typingTimer = setTimeout(this.getResults.bind(this), 1000);
                    }   else {
                            this.resultsDiv.html('');
                            this.isSpinnerVisible = false;
                            this.resultsHover.html('');
                            this.isSpinnerVisible = false;
                    }
                    
                }     
                this.previousValue = this.searchField.val();
            }
        
            getResults() {
                
                let impArray = {
                    terms: [],
                };     
                
                $(".importent-range").each(function(){
                    if(this.checked) {
                        impArray.terms.push(this.value);
                    }
                    
                });
                let importArr = encodeURIComponent(JSON.stringify(impArray)); // IMPORTANCE
                let date_from = $(".datafrom").val(); //DATAFROM
                let date_to = $(".datato").val(); //DATATO
                
                let url = searchData.root_url + '/wp-json/dmv_old_events/v2/search?term=' + this.searchField.val() + '&date_from=' + date_from + '&date_to=' + date_to + '&importance=' + importArr; 

                $.getJSON(url, (eventsResults) => {
                    this.resultsDiv.html(`
                    <div class="row">
                        <div class="one-third">
                            <h2 class="search-overlay__section-title">Old Events:</h2>
                            ${eventsResults.length ? '<ul class="item-list">' : '<p>Not events</p>'}
                            ${eventsResults.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>
                            ${item.events.map(post => `<li><a href="${post.permalink}">${post.title}</a></li>`).join('')}
                            ${item.peoples.map(people => `<li><span>${people.NameItem} ${people.SurnameItem}</span> <a href="${people.SocialItem}">${people.SocialItem}</a></li>`).join('')}
                            
                            ` ).join('')}            
                            ${eventsResults.length ? '</ul>' : ''}
                        </div>
                        <div class="one-third">

                        </div>
                    </div>
                    
                    `);
                    this.isSpinnerVisible = false;
                });  
            }
        
            openOverlay() {
                this.searchOverlay.addClass("search-overlay--active");
                $("body").addClass("body-no-scroll");
                
            }
        
            closeOverlay() {
                this.searchOverlay.removeClass("search-overlay--active");
                $("body").removeClass("body-no-scroll");
            }
        }
            var search = new Search();        
        });