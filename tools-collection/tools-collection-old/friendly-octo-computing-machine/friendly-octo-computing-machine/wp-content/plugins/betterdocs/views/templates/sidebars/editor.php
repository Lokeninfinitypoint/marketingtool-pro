<script>
    jQuery(document).ready(function($) {
        let listSidebarCatTitles = $(
            ".betterdocs-sidebar-content .betterdocs-sidebar-list-wrapper .betterdocs-sidebar-list .betterdocs-category-header"
        );
        let catTitleList = $(
            ".betterdocs-sidebar-content .betterdocs-category-grid-wrapper .betterdocs-single-category-wrapper .betterdocs-category-header"
        );
        let currentActiveCat = $(
            ".betterdocs-sidebar-content .betterdocs-category-grid-wrapper .betterdocs-single-category-wrapper.active"
        );
        let listSidebarCurrentCat = $(
            ".betterdocs-sidebar-content .betterdocs-sidebar-list-wrapper .betterdocs-sidebar-list.active"
        );

        let nestedCategoriesToggle = $(".betterdocs-nested-category-title");

        function categoryAccordion() {
            let $parentThis = this;
            currentActiveCat
                .addClass("show")
                .find(".betterdocs-body")
                .css("display", "block");
            currentActiveCat
                .siblings()
                .find(".betterdocs-body")
                .css("display", "none");
            catTitleList.on("click", function (e) {
                e.preventDefault();
                let $parentCat = jQuery(e.target).closest(
                    ".betterdocs-single-category-wrapper"
                );
                $parentCat.find(".betterdocs-body").slideToggle();
                $parentCat
                    .addClass("active")
                    .toggleClass("show")
                    .siblings()
                    .removeClass("active")
                    .find(".betterdocs-body")
                    .slideUp();
            });
        }
        categoryAccordion();

        function categoryListAccordion() {
            let $parentThis = this;
            listSidebarCurrentCat
                .find(".betterdocs-body")
                .css("display", "block");
            listSidebarCurrentCat
                .siblings()
                .find(".betterdocs-body")
                .css("display", "none");
            listSidebarCatTitles.on("click", function (e) {
                console.log(e.target);
                e.preventDefault();
                let $parentCat = jQuery(e.target).closest(
                    ".betterdocs-sidebar-list"
                );
                $parentCat.find(".betterdocs-body").slideToggle();
                $parentCat
                    .toggleClass("active")
                    .siblings()
                    .removeClass("active")
                    .find(".betterdocs-body")
                    .slideUp();
            });
        }
        categoryListAccordion();

        function nestedCategoryToggler() {
            nestedCategoriesToggle.on("click", function (e) {
                e.preventDefault();
                jQuery(this).children(".toggle-arrow").toggle();
                jQuery(this).next(".betterdocs-nested-category-list").slideToggle();
            });
        }
        nestedCategoryToggler();
    });
</script>


