<?php
namespace Nano\Core;

trait RouteView
{
	# ------------------------------------------ ------------------------------------------ #
	public final function view( $destination )
	{
		$this->view = $destination;
		
		foreach ( $this->args->list() as $args_var_name => $_ )
		{
			$$args_var_name = $this->args->$args_var_name;
		}
		
		if ( isset( $layout ) && $layout )
		{
			$this->setLayout( $layout );
		}
		
		ob_start();
		include( $this->view_path . $this->view );
		$view = ob_get_contents();
		ob_end_clean();
		
		if ( $this->layout )
		{
			ob_start();
			include( $this->layout );
			$html = ob_get_contents();
			ob_end_clean();
		}
		else
		{
			$html = $view;
		}
		
		echo $html;
		exit;
	}
}