<?php
	$menuSelection = array(
		"proxy_search" => array(
			"task" => "select_data",
			"text" => "Data Selection",
			"subpages" => array()
		)
		
        );
        

	$menuAnnotation = array(
                "imputatation" => array(
			"task" => "imputation",
			"text" => "Imputation",
			"subpages" => array()
		),
		"snp_annotation" => array(
			"task" => "normalization",
			"text" => "Normalization",
			"subpages" => array()
		)            
                );
	$menuPlot = array(
              "boxplot" => array(
			"task" => "boxplot",
			"text" => "Box Plot",
			"subpages" => array()
		),
		"pca_plot_two" => array(
			"task" => "pcaplot2d",
			"text" => "PCA Plot(2D)",
			"subpages" => array()
		),
		"pca_plot_three" => array(
			"task" => "pcaplot3d",
			"text" => "PCA Plot(3D)",
			"subpages" => array()
		)
                );
        $menustaticalAnalysis = array(
                "lm" => array(
                        "task" => "lm",
                        "text" => "lm",
                        "subpages" => array()
                ),
                "glm" => array(
                        "task" => "glm",
                        "text" => "glm",
                        "subpages" => array()
                ));

	$menuHelp = array(
		"documentation" => array(
			"task" => "documentation",
			"text" => "Documentation",
			"subpages" => array()
		),
		"about_snipa" => array(
			"task" => "about_canceromics",
			"text" => "About Canceromics",
			"subpages" => array()
		));
?>
